import React, { createContext, useContext, useState, useEffect } from 'react';
import type { Lang } from './i18n';

type Page =
  | 'design-system'
  | 'home' | 'stores' | 'store-detail' | 'search' | 'auth-signin' | 'auth-signup' | 'blog' | 'blog-article'
  | 'dashboard' | 'withdrawal'
  | 'admin-dashboard' | 'admin-add-store' | 'admin-stores' | 'admin-offers' | 'admin-transactions' | 'admin-users' | 'admin-payouts' | 'admin-reports';

interface AppState {
  dark: boolean;
  lang: Lang;
  page: Page;
  showCookie: boolean;
  toast: { msg: string; type: 'success' | 'info' | 'error' } | null;
  setDark: (v: boolean) => void;
  setLang: (v: Lang) => void;
  setPage: (v: Page) => void;
  setShowCookie: (v: boolean) => void;
  showToast: (msg: string, type?: 'success' | 'info' | 'error') => void;
}

const Ctx = createContext<AppState>({} as AppState);
export const useApp = () => useContext(Ctx);

export function AppProvider({ children }: { children: React.ReactNode }) {
  const [dark, setDarkState] = useState(false);
  const [lang, setLang] = useState<Lang>('en');
  const [page, setPage] = useState<Page>('home');
  const [showCookie, setShowCookie] = useState(true);
  const [toast, setToast] = useState<AppState['toast']>(null);

  const setDark = (v: boolean) => {
    setDarkState(v);
    document.documentElement.classList.toggle('dark', v);
  };

  useEffect(() => {
    document.documentElement.classList.toggle('dark', dark);
  }, []);

  const showToast = (msg: string, type: 'success' | 'info' | 'error' = 'success') => {
    setToast({ msg, type });
    setTimeout(() => setToast(null), 3000);
  };

  return (
    <Ctx.Provider value={{ dark, lang, page, showCookie, toast, setDark, setLang, setPage, setShowCookie, showToast }}>
      {children}
    </Ctx.Provider>
  );
}
