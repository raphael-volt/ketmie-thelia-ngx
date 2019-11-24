<?php

class ProcessPaiement
{

    /**
     *
     * @param mixed $perso_valeur
     * @return BO_Carac
     */
    public static function getBoDecli($perso_valeur, PDO $pdo = null)
    {
        $id = intval($perso_valeur);
        if (! $pdo)
            $pdo = PDOThelia::getInstance();
        $q = "SELECT * FROM bo_carac WHERE id=?";
        $stmt = $pdo->prepare($q);
        $pdo->bindInt($stmt, 1, $id);
        return $pdo->fetch($stmt, BO_Carac::class, true);
    }

    public static function save(Navigation $navig, $type_paiement)
    {
        $total = 0;
        $nbart = 0;
        $poids = 0;
        $unitetr = 0;
        
        $modules = new Modules();
        $modules->charger_id($type_paiement);
        
        if (! $modules->actif)
            return 0;
        
        try {
            
            $modpaiement = ActionsModules::instance()->instancier($modules->nom);
            
            $commande = new Commande();
            $commande->transport = $navig->commande->transport;
            $commande->client = $navig->client->id;
            $commande->remise = 0;
            
            $devise = ActionsDevises::instance()->get_devise_courante();
            $commande->devise = $devise->id;
            $commande->taux = $devise->taux;
            
            $client = new Client();
            $client->charger_id($navig->client->id);
            
            $adr = new Venteadr();
            $adr->raison = $client->raison;
            $adr->entreprise = $client->entreprise;
            $adr->nom = $client->nom;
            $adr->prenom = $client->prenom;
            $adr->adresse1 = $client->adresse1;
            $adr->adresse2 = $client->adresse2;
            $adr->adresse3 = $client->adresse3;
            $adr->cpostal = $client->cpostal;
            $adr->ville = $client->ville;
            $adr->tel = $client->telfixe . "  " . $client->telport;
            $adr->pays = $client->pays;
            $adrcli = $adr->add();
            $commande->adrfact = $adrcli;
            
            $adr = new Venteadr();
            $livraison = new Adresse();
            
            if ($livraison->charger($navig->adresse)) {
                
                $adr->raison = $livraison->raison;
                $adr->entreprise = $livraison->entreprise;
                $adr->nom = $livraison->nom;
                $adr->prenom = $livraison->prenom;
                $adr->adresse1 = $livraison->adresse1;
                $adr->adresse2 = $livraison->adresse2;
                $adr->adresse3 = $livraison->adresse3;
                $adr->cpostal = $livraison->cpostal;
                $adr->ville = $livraison->ville;
                $adr->tel = $livraison->tel;
                $adr->pays = $livraison->pays;
            } else {
                $adr->raison = $client->raison;
                $adr->entreprise = $client->entreprise;
                $adr->nom = $client->nom;
                $adr->prenom = $client->prenom;
                $adr->adresse1 = $client->adresse1;
                $adr->adresse2 = $client->adresse2;
                $adr->adresse3 = $client->adresse3;
                $adr->cpostal = $client->cpostal;
                $adr->ville = $client->ville;
                $adr->tel = $client->telfixe . "  " . $client->telport;
                $adr->pays = $client->pays;
            }
            
            $adrlivr = $adr->add();
            $commande->adrlivr = $adrlivr;
            
            $commande->facture = 0;
            
            $commande->statut = Commande::NONPAYE;
            $commande->paiement = $type_paiement;
            
            $commande->lang = ActionsLang::instance()->get_id_langue_courante();
            
            $commande->id = $commande->add();
            
            $pays = new Pays();
            $pays->charger($adr->pays);
            
            $correspondanceParent = array(
                null
            );
            
            foreach ($navig->panier->tabarticle as $pos => &$article) {
                $venteprod = new Venteprod();
                
                $dectexte = array();
                
                $produit = new Produit();
                
                $stock = new Stock();
                
                foreach ($article->perso as $perso) {
                    
                    $perso instanceof Perso;
                    if (is_numeric($perso->valeur) && $modpaiement->defalqcmd) {
                        
                        // diminution des stocks de déclinaison si on est sur un module de paiement qui défalque de suite
                        $stock->charger($perso->valeur, $article->produit->id);
                        $stock->valeur -= $article->quantite;
                        $stock->maj();
                    }
                    if ($perso->declinaison == BO_DECLINAISON) {
                        $carac = self::getBoDecli($perso->valeur);
                        $bo = new BODecli();
                        $bo->size = $carac->size;
                        $dectexte[] = "taille : " . $bo->getName();
                        continue;
                    }
                    // STANDARD_DECLINAISON;
                    $declinaison = new Declinaison();
                    $declinaisondesc = new Declinaisondesc();
                    
                    $declinaison->charger($perso->declinaison);
                    $declinaisondesc->charger($declinaison->id);
                    
                    // recup valeur declidisp ou string
                    if ($declinaison->isDeclidisp($perso->declinaison)) {
                        $declidisp = new Declidisp();
                        $declidispdesc = new Declidispdesc();
                        $declidisp->charger($perso->valeur);
                        $declidispdesc->charger_declidisp($declidisp->id);
                        $dectexte[] = $declinaisondesc->titre . " : " . $declidispdesc->titre;
                    } else
                        $dectexte[] = $declinaisondesc->titre . " : " . $perso->valeur;
                }
                
                // diminution des stocks classiques si on est sur un module de paiement qui défalque de suite
                
                $produit = new Produit($article->produit->ref);
                
                if ($modpaiement->defalqcmd) {
                    $produit->stock -= $article->quantite;
                    $produit->maj();
                }
                
                // Gestion TVA
                $prix = $article->produit->prix;
                $prix2 = $article->produit->prix2;
                $tva = $article->produit->tva;
                
                if ($pays->tva != "" && (! $pays->tva || ($pays->tva && $navig->client->intracom != "" && ! $pays->boutique))) {
                    $prix = round($prix / (1 + ($tva / 100)), 2);
                    $prix2 = round($prix2 / (1 + ($tva / 100)), 2);
                    $tva = 0;
                }
                
                $venteprod->quantite = $article->quantite;
                
                if (! $article->produit->promo)
                    $venteprod->prixu = $prix;
                else
                    $venteprod->prixu = $prix2;
                
                $venteprod->ref = $article->produit->ref;
                if (count($dectexte))
                    $dectexte = " (" . explode(", ", $dectexte) . ")";
                else
                    $dectexte = "";
                $venteprod->titre = $article->produitdesc->titre . $dectexte;
                $venteprod->chapo = $article->produitdesc->chapo;
                $venteprod->description = $article->produitdesc->description;
                $venteprod->tva = $tva;
                
                $venteprod->commande = $commande->id;
                $venteprod->id = $venteprod->add();
                $correspondanceParent[] = $venteprod->id;
                
                // ajout dans ventedeclisp des declidisp associées au venteprod
                foreach ($article->perso as $perso) {
                    $declinaison = new Declinaison();
                    $declinaison->charger($perso->declinaison);
                    
                    // si declidisp (pas un champs libre)
                    if ($perso->declinaison == BO_DECLINAISON || $declinaison->isDeclidisp($perso->declinaison)) {
                        $vdec = new Ventedeclidisp();
                        $vdec->venteprod = $venteprod->id;
                        $vdec->declidisp = $perso->valeur;
                        $vdec->add();
                    }
                }
                
                ActionsModules::instance()->appel_module("apresVenteprod", $venteprod, $pos);
                $total += $venteprod->prixu * $venteprod->quantite;
                $nbart ++;
                $poids += $article->produit->poids;
            }
            // self::addLog("TOTAL_COMMANDE:$total");
            foreach ($correspondanceParent as $id_panier => $id_venteprod) {
                
                if ($navig->panier->tabarticle[$id_panier]->parent >= 0) {
                    
                    $venteprod->charger($id_venteprod);
                    $venteprod->parent = $correspondanceParent[$navig->panier->tabarticle[$id_panier]->parent];
                    $venteprod->maj();
                }
            }
            
            $pays = new Pays($navig->client->pays);
            
            if ($navig->client->pourcentage > 0)
                $commande->remise = $total * $navig->client->pourcentage / 100;
            
            $total -= $commande->remise;
            
            if ($navig->promo->id != "") {
                
                $commande->remise += calc_remise($total);
                
                $navig->promo->utilise = 1;
                
                if (! empty($commande->remise))
                    $commande->remise = round($commande->remise, 2);
                
                $commande->maj();
                $temppromo = new Promo();
                $temppromo->charger_id($navig->promo->id);
                
                $temppromo->utilise ++;
                
                $temppromo->maj();
                
                $promoutil = new Promoutil();
                $promoutil->commande = $commande->id;
                $promoutil->promo = $temppromo->id;
                $promoutil->code = $temppromo->code;
                $promoutil->type = $temppromo->type;
                $promoutil->valeur = $temppromo->valeur;
                $promoutil->add();
            }
            
            if ($commande->remise > $total)
                $commande->remise = $total;
            
            $commande->port = port();
            if (intval($commande->port) <= 0)
                $commande->port = 0;
            
            $navig->promo = new Promo();
            $navig->commande = $commande;
            
            $commande->transaction = genid($commande->id, 6);
            
            $commande->maj();
            
            $total = $navig->panier->total(1, $navig->commande->remise) + $navig->commande->port;
            
            if ($total < $navig->commande->port)
                $total = $navig->commande->port;
            
            $navig->commande->total = $total;
            
            TheliaDeliveryMailer::sendMails($commande);
            
            ActionsModules::instance()->appel_module("confirmation", $commande);
            $_SESSION["navig"]->panier = new Panier();
            
            ActionsModules::instance()->appel_module("aprescommande", $commande);
        } catch (Exception $e) {
            // FIXME: Echec de commande -> cas à traiter ?
        }
    }
}

