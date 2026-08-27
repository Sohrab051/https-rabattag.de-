import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';

export default function Navbar() {
  const { dark, setDark, lang, setLang, setPage, page } = useApp();
  const tr = t(lang);
  const [menuOpen, setMenuOpen] = useState(false);
  const isAdmin = page.startsWith('admin');
  const isAuth = page.startsWith('auth');

  if (isAuth) return null;

  return (
    <header className={`sticky top-0 z-50 border-b transition-colors ${dark ? 'bg-slate-900/95 border-slate-700/60 backdrop-blur' : 'bg-white/95 border-slate-200/80 backdrop-blur'}`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6">
        <div className="flex items-center justify-between h-14 sm:h-16">
          {/* Logo */}
          <button onClick={() => setPage('home')} className="flex items-center gap-2 font-display font-800 text-lg sm:text-xl">
            <span className="flex items-center justify-center w-8 h-8 bg-primary-700 rounded-lg text-white text-sm font-bold">CB</span>
            <span className={dark ? 'text-white' : 'text-slate-900'}>CashbackHub</span>
          </button>

          {/* Desktop nav */}
          {!isAdmin && (
            <nav className="hidden md:flex items-center gap-1">
              {[
                { key: 'home' as const, label: tr.nav.home },
                { key: 'stores' as const, label: tr.nav.stores },
                { key: 'blog' as const, label: tr.nav.blog },
              ].map(({ key, label }) => (
                <button
                  key={key}
                  onClick={() => setPage(key)}
                  className={`px-3 py-1.5 rounded-lg text-sm font-medium transition-colors ${
                    page === key
                      ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300'
                      : dark ? 'text-slate-300 hover:text-white hover:bg-slate-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                  }`}
                >
                  {label}
                </button>
              ))}
            </nav>
          )}

          {isAdmin && (
            <span className={`hidden md:block text-sm font-semibold ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
              Admin Panel
            </span>
          )}

          {/* Right controls */}
          <div className="flex items-center gap-2 sm:gap-3">
            {/* Search */}
            {!isAdmin && (
              <button
                onClick={() => setPage('search')}
                className={`hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border text-sm transition-colors ${dark ? 'border-slate-700 text-slate-400 hover:bg-slate-800' : 'border-slate-200 text-slate-500 hover:bg-slate-50'}`}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <span className="hidden lg:block text-xs">{tr.nav.search}</span>
              </button>
            )}

            {/* Lang switcher */}
            <button
              onClick={() => setLang(lang === 'en' ? 'de' : 'en')}
              className={`flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs font-semibold transition-all ${dark ? 'border-slate-700 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}
              title="Switch language / Sprache wechseln"
            >
              <span>{lang === 'en' ? '🇬🇧' : '🇩🇪'}</span>
              <span>{lang.toUpperCase()}</span>
            </button>

            {/* Dark mode */}
            <button
              onClick={() => setDark(!dark)}
              className={`p-2 rounded-lg transition-colors ${dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-500 hover:bg-slate-100'}`}
              title={dark ? 'Light mode' : 'Dark mode'}
            >
              {dark ? (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
              ) : (
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
              )}
            </button>

            {/* Auth / dashboard */}
            {!isAdmin && (
              <>
                <button
                  onClick={() => setPage('auth-signin')}
                  className={`hidden sm:block px-3 py-1.5 text-sm font-medium rounded-lg transition-colors ${dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-100'}`}
                >
                  {tr.nav.login}
                </button>
                <button
                  onClick={() => setPage('dashboard')}
                  className="px-3 py-1.5 text-sm font-semibold rounded-lg bg-primary-700 text-white hover:bg-primary-800 transition-colors"
                >
                  <span className="hidden sm:inline">{tr.nav.signup}</span>
                  <span className="sm:hidden">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                  </span>
                </button>
              </>
            )}

            {/* Mobile menu */}
            <button
              onClick={() => setMenuOpen(!menuOpen)}
              className={`md:hidden p-2 rounded-lg ${dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-100'}`}
            >
              {menuOpen ? (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              ) : (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>
              )}
            </button>
          </div>
        </div>

        {/* Mobile menu */}
        {menuOpen && (
          <div className={`md:hidden border-t pb-3 ${dark ? 'border-slate-700' : 'border-slate-200'}`}>
            <nav className="flex flex-col gap-1 pt-2">
              {[
                { key: 'home' as const, label: tr.nav.home },
                { key: 'stores' as const, label: tr.nav.stores },
                { key: 'search' as const, label: tr.common.search },
                { key: 'blog' as const, label: tr.nav.blog },
                { key: 'dashboard' as const, label: tr.nav.dashboard },
                { key: 'admin-dashboard' as const, label: tr.nav.admin },
              ].map(({ key, label }) => (
                <button
                  key={key}
                  onClick={() => { setPage(key); setMenuOpen(false); }}
                  className={`w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                    page === key
                      ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300'
                      : dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-100'
                  }`}
                >
                  {label}
                </button>
              ))}
            </nav>
          </div>
        )}
      </div>
    </header>
  );
}
