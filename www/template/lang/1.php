
<?php
$collageSize0 = "40 cm";
$collageSize1 = "60 cm";
function getCollageDescription($w, $h)
{
	return <<<EOL
<dl class="collageDesc">
	<dt>Dimensions</dt>
	<dd>
		<ul>
			<li>Largeur : $w</li>
			<li>Hauteur : $h</li>
		</ul>
	</dd>
</dl>
EOL;
}

$boinfo1 = <<<EOL
<p>Les boucles d'oreilles sont fabriquées en <span class="bold">zinc</span>, finission brossée.</p>
EOL;
$boinfo2 = <<<EOL
<p class="bold">Choisissez une taille :</p>
EOL;
$collageInfo = <<<EOL
<p>Les collages sont imprimés sur un support en <span class="bold">aluminium</span>.</p>
<p>Articles numérotés, 7 exemplaires.</p>
EOL;
$declimetal = <<<EOL
<p class="bold">Ces boucles d'oreilles sont disponibles en plusieurs <span class="bold">métaux</span>.</p>
EOL;

	$GLOBALS['dicotpl'] = array(
		
		'euro'=>'€',
		'annuler'=>'Annuler',
		'nextstep' => 'Étape suivante',
		'titre' => 'Titre',
		'compte' => 'Mon compte',
		'connexion' => 'Me connecter',
		'createaccount' => 'Créer mon compte',
		//contenu
		'declimetal' => $declimetal,
		'boinfo-declimetal-select' => "Choisissez un métal :",
		'boinfo-declisize-select' => "Choisissez une taille :",
		'boinfo' => $boinfo,
		'boinfo1' => $boinfo1,
		'boinfo2' => $boinfo2,
		'collageinfo' => $collageInfo,
		'colinfoportait' => getCollageDescription($collageSize0, $collageSize1),
		'colinfopaysage' => getCollageDescription($collageSize1, $collageSize0),
		'navigation' => 'Vous êtes ici',
		'accueil' => 'Accueil boutique',
		'liresuite' => 'Lire la suite de',
		'voirtousarticles' => 'Voir tous les articles',
		'retourarticles' => 'Retour à la liste des articles',
		'derniersarticlesdos' => 'Derniers articles',
		'contenudos' => 'Contenu de la rubrique',
		'dossiers' => 'Dossiers',
		'selfconnexion' => 'Je me connecte',
		'selfaccount' => 'Je crée mon compte',
		
		//index_page
		'changprix' => 'au lieu de',
		'infosup' => 'En savoir +',
		'commander' => 'Commander',
		'nouveautes2' => 'Voir toutes les nouveautés',
		'promo' => 'Promotions',
		'promo2' => 'Voir toutes les promotions',
		'decouvrez' => 'Découvrez ...',
		'derniersarticles' => 'DERNIERS ARTICLES',
		'promotions' => 'PROMOTIONS',
		'nouveautes' => 'NOUVEAUT&Eacute;S',
		'nouveaute' => 'NOUVEAUT&Eacute;',
		'nouveau' => 'NOUVEAU',
		
		//adresse
		'livraison' => 'Livraison',
		'facturation' => 'Facturation',
		'modifier' => 'Modifier',
		'autreadresse' => 'Sélectionner une autre adresse',
		'coordonnees' => 'Mes coordonnées',
		'panier' => 'PANIER',
		'facturationlivraison' => 'FACTURATION ET LIVRAISON',
		'verifcommande' => 'JE VERIFIE MA COMMANDE',
		'paiementsecurise' => 'PAIEMENT SECURISE',
		'adressefacturation' => 'Mon adresse de facturation',
		'modifadresse' => 'Modifier cette adresse',
		'adresselivraison' => 'Mon adresse de livraison',
		//à rajouter
		'noaddess' => 'Vous n\'avez pas ajouté d\'adresse.',
		'modifautreadresselivraison' => 'Modifier une autre adresse de livraison',
		//fin rajout
		'adressefacturationdefaut' => 'Mon adresse de facturation par défaut',
		'ajoutadresse'=>'Ajouter une adresse',
		'ajoutnouvelleadresse' => 'Ajouter une nouvelle adresse de livraison',
		'choixmodelivraison' => 'Sélectionnez un mode de livraison',
		'modelivraison' => 'Je choisis ce mode de livraison',
		
		//cheque
		'choixmodereglementcheque' => 'Je choisis un mode de règlement : par chèque',
		'remerciement' => 'Nous vous remercions de la confiance que vous nous accordez.',
		'emailrecapitulatif' => 'Un email recapitulatif de votre commande vous a été envoyé à l\'adresse:',
		'validationcommande' => 'Votre commande sera validée par nos services à réception de votre paiement.',
		'inforeglement' => 'Ecrire ici les infos sur le réglement par chèque ...',
		'date' => 'DATE',
		
		//commande
		'recapitulatifcommande' => 'Récapitulatif de ma commande',
		'nomarticle' => 'Nom de l\'article',
		'nomarticle2' => 'Articles',
		'prixunitaire' => 'Prix unitaire',
		'quantite' => 'Quantité',
		'total' => 'Total',
		'fraislivraison' => 'Frais de livraison',
		'remise' => 'Remise',
		'codereduc' => 'Avez-vous un code de réduction ?',
		'choixmodepaiement' => 'Mode de paiement',
		'choixmodepaiement2' => 'Je choisis ce mode de paiement',
		
		//commande_detail
		'validcommande' => 'Validation de ma commande',
		'detailcommande' => 'Détail de la commande N°',
		'numfacture' => 'N° de facture :',
		'produitreference' => 'Produit et référence',
		'facturepdf' => 'Visualiser la facture au format PDF',
		'pageprecedente' => 'Page précédente',
		'totalcommande' => 'Total commande',
		
		//compte-modifier
		'connexionmodifier' => 'Modifier mes données de connexion',
		'comptemodifier' => 'Mon compte : modifier mes informations',
		'modifiermdp' => 'Modifier mon mot de passe',
		'modifieremail' => 'Modifier mon email',
		'nouveaumdp' => 'Nouveau mot de passe',
		'confirmationmdp' => 'Confirmation du mot de passe',
		'valider' => 'Valider',
		'modifcoord' => 'Modifier mes coordonnées',
		'civilite' => 'Civilité',
		'choisissez' => 'Choisissez ...',	
		'madame' => 'Madame',
		'mademoiselle' => 'Mademoiselle',
		'monsieur' => 'Monsieur',
		'nom' => 'Nom',
		'prenom' => 'Prénom',
		'adresse' => 'Adresse',
		'complementadresse' => 'Complément d\'adresse',
		'cp' => 'Code postal',
		'ville' => 'Ville',
		'pays' => 'Pays',
		'tel' => 'Téléphone',
		'telfixe' => 'Téléphone fixe',
		'telport' => 'Téléphone portable',
		'confirmationemail' => 'Confirmation de l\'e-mail',
		'confirmationmdp' => 'Confirmation du mot de passe',
		
		//compte_modifiererr
		'monmdp' => 'Mot de passe',
		'verifmdp' => '(Vérifiez votre mot de passe. 6 car mini)',
		'obligatoire' => '(obligatoire)',
		'verifemail' => '(Vérifiez votre E-Mail)',
		//a traduire
		'inexistant' => 'Mail Inexistant',
		'valideemail' => 'Votre demande a été prise en compte. Vous allez recevoir par mail votre nouveau mot de passe.<br />
			Merci de votre compréhension.',
		//fin
		
		//connexion
		'identif' => 'Identifiez-vous',
		'monemail' => 'E-mail',
		'emailmdperr' => 'E-mail ou mot de passe incorrects',
		'nvclient' => 'Nouveau client ?',
		'tel' => 'Téléphone',
		'mdp' => 'Mot de passe* (6 caractères minimum )',
		'champsobligat' => 'Champs obligatoires',
		'dejaclient' => 'Déjà client ?',
		'email' => 'Email',
		'deconnect' => 'Déconnexion',
		'compteexistant' => 'Ce compte existe déjà',
		
		//contact
		'contactemail' => 'Nous contacter par e-mail',
		'noustrouver' => 'Nous trouver',
		'coord' => 'Nos coordonnées',
		'nomentr' => 'Nom de l\'entreprise',
		'adresseentr' => 'Adresse de l\'entreprise',
		'votremess' => 'Votre message',
		'suggestions' => 'Vos suggestions et remarques nous intéressent. Contactez-nous en complétant le formulaire ci-dessous :',
		'votrenom' => 'Votre nom',
		'votreemail' => 'Votre e-mail',
		'sujetmess' => 'Le sujet de votre message',
		'envoyer' => 'Envoyer',
		
		//entete
		'achats' => 'Mes achats',
		'contient' => 'Votre panier contient',
		'article' => 'article(s)',
		'paniervide' => 'Votre panier est vide',
		'mdpperdu' => 'Mot de passe perdu ?',
		'mdpperduououblie' => 'perdu ou oublié',
		'mailnotfound' => 'Aucun compte associé à cet e-mail n\'a été trouvé.<br/>Saisissez à nouveau votre e-mail.',
		'voircpte' => 'Voir mon compte',
		'deconnecter' => 'Me déconnecter',
		'bonjour' => 'Bonjour',
		'errsaisie' => 'des erreurs dans votre saisie !',
		'relisez' => 'Relisez les informations que vous souhaitez  nous transmettre. Vous avez sans doute fait une erreur.',
		'mdp1' => 'Mot de passe',
		'mdp4' => '(Vérifiez votre mot de passe. 4 caractères  minimum)',
		'mincarac' => '(4 caractères minimum)',
	
		//livraison_adresse
		'adresseslivraison' => 'Mes adresses de livraison',
		'ajouteradresse' => 'Ajouter une adresse de livraison',
		'libelle' => 'Libellé de l\'adresse',
		
		//livraison_modifier
		'modifadresselivraison' => 'Modifier une adresse de livraison',
		
		//mdpoublie
		'mdpperduoublie' => 'Mot de passe perdu ou oublié',
		'inscrivez' => 'Inscrivez votre email pour recevoir votre mot de passe',
		
		//menu
		'recherche' => 'Recherche',
		'rechercheavancee' => 'Recherche avancée',
		'service' => 'A votre service',
		'rubriquessousrub' => 'Rubriques / Sous-rubriques',
		'carac' => 'Caractéristiques',
		'declinaisons' => 'Déclinaisons',
		'rechercher' => 'Rechercher',
		
		//merci
		'merci' => 'Merci',
		'remerciements' => 'Notre boutique vous remercie pour votre achat.',
		'abientot' => 'Bonne navigation et à bientôt.',
		'retourpageaccueil' => 'Retour en page d\'accueil',
		
		//moncompte
		'modifcomptemdp' => 'Modifier mon compte et mon mot de passe',
		'mescommandes' => 'Mes commandes',
		'numcommande' => 'N° de la commande',
		'montant' => 'Montant',
		'statut' => 'Statut',
		'voircommande' => 'Voir la commande',
		'nocommande' => 'Vous n\'avez aucune commande',
		
		//nouveau
		'creationcompte' => 'Création de mon compte',
		'felicitation' => 'Félicitations, vous venez de terminer la création de votre compte !',
		'retourpp' => 'Retour à la page précédente',
		
		//panier
		'contenu' => 'Contenu de mon panier',
		'nomproduit' => 'Contenu de mon panier',
		'totalttc' => 'Total TTC',
		'vider' => 'Vider',
		'supprimer' => 'Supprimer',
		'totalpanier' => 'Total panier',
		'retourboutique' => 'Retour à la boutique',
		'finalisercommande' => 'Finaliser ma commande',
		'paniervide' => 'Il n\'y a pas de produits dans votre panier.',
		
		//pied
		'resterinfo' => 'Rester informé',
		'filinfo' => 'Fil d\'information',
		'contact' => 'Contact',
		'social' => 'NOUS SUIVRE SUR LES RESEAUX SOCIAUX',
		'suivre' => 'Suivre',
		'newsletter' => 'Inscrivez-vous à notre newsletter pour suivre nos actualités',
		'mentionslegales' => 'Mentions Légales',
		'cgv' => 'Conditions Générales de Vente',
		'faq' => 'FAQ',
		'paiementsecurisepied' => 'Paiement sécurisé',
		'livraisonrapide' => 'Livraison rapide : de 24h à 72h',
		'fraisofferts' => 'Frais de livraison offerts',
		'voirtous' => 'Voir tous les produits',
		'commandedetaillee' => 'Commande détaillée',
		'paiementmulti' => 'Paiement multi-plateformes',
		'infosboutique' => 'Cette boutique utilise le logiciel libre <a href="http://thelia.net" target="_blank">Thelia</a>',
		'dernieresactus' => 'Dernières actus',
		'moyenspaiement' => 'Moyens de paiement',
		'transport' => 'Transports',
		
		//produit
		'ajouterpanier' => 'Ajouter au panier',
		'telechargement' => 'Téléchargement(s)',
		'autreproposition' => 'Nous vous proposons également',
		'rupturestock' => 'Rupture de stock',
		'description' => 'Description',
		'caracteristiques' => 'Caractéristiques',
		
		
		//thickbox
		'produitajoutepanier' => 'Le produit a été ajouté au panier !',
		'quefaire' => 'Que souhaitez-vous faire ?',
		'validerpanier' => 'Voir mon panier et finaliser ma commande',
		'poursuivreachats' => 'Poursuivre mes achats',
		
		//superbox
		'fermer' => 'Fermer',
		'imgsuiv' => 'Image suivante',
		'imgprec' => 'Image précédente',
		'chargement' => 'Chargement',
		
		//recherche
		'votrerecherche' => 'Votre recherche',
		'pagesuivante' => 'Page suivante',
		'aucunresultat' => 'Nous sommes désolés, aucun résultat ne correspond à votre recherche.',
		'action' => 'Action',
		
		//rubrique
		'decouvrir' => 'Découvrir',
		
		//regret
		'erreurpaiement' => 'Erreur de paiement',
		'nonpaiement' => 'Nous vous informons que la procédure de paiement n\'a pas pu aboutir.',
		'renseignementbanque' => 'Veuillez vous renseigner auprès de votre établissement bancaire.',
		
		//signature
		'chartegraphique' => 'Charte graphique',
		'integrationtechnique' => 'Intégration technique',
		'propulse' => 'Propulsé par',
		'paiementpossible' => 'Paiement possible avec',
		
		// chèque	
		'paiementadresse'=>'Envoyez votre chèque à cette adresse :',
		'reglementcheque' => 'Mode de règlement : par chèque',
		'infocheque' => 'Merci de joindre la référence de la commande à votre courrier :',
		//virement
		'reglementvirement' => 'Mode de règlement : par virement',
		'ecrireicinfo' => 'Ecrire ici les infos sur le réglement par virement ...',
		'numcommande2' => 'N° DE COMMANDE',
		'refcommande' => 'RÉFÉRENCE DE LA COMMANDE',
		
		//moteur de recherche avancé
        'raz_multicritere' => 'Réinitialiser',
		'moteuravance_erreur_rubrique' => 'Veuillez sélectionner une rubrique.',
		'moteuravance_erreur_sousrubrique' => 'Veuillez sélectionner au moins une sous-rubrique.'
		
		
		
		
);

?>
