import { Injectable } from '@angular/core';
import { LocalStorageService } from 'angular-2-local-storage';

type SessionCookie = {
  session_id: string;
};

const COOKIE_ID: string = 'api-session';

@Injectable()
export class SessionService {
  private _sessionCookie: SessionCookie;

  get hasSession() {
    return this._sessionCookie != undefined && this._sessionCookie.session_id.length > 0;
  }

  get sessionId(): string {
    if (this.hasSession) return this._sessionCookie.session_id;
    return null;
  }

  constructor(private storage: LocalStorageService) {
    this._sessionCookie = this.cookie;
  }

  update(sessionId: string) {
    if (!this._sessionCookie) this._sessionCookie = { session_id: sessionId };
    else {
      if (this._sessionCookie.session_id == sessionId) return;
      this._sessionCookie.session_id = sessionId;
      console.warn('[SessionService] SESSION_ID CHANGED');
      this.storage.set(COOKIE_ID, this._sessionCookie);
    }
  }

  private get cookie(): SessionCookie {
    return this.storage.get<SessionCookie>(COOKIE_ID);
  }
}
