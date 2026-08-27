import type { ReactNode } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';

type AdminPage = 'admin-dashboard' | 'admin-add-store' | 'admin-stores' | 'admin-offers' | 'admin-transactions' | 'admin-users' | 'admin-payouts' | 'admin-reports';

const navItems: { key: AdminPage; icon: string }[] = [
  { key: 'admin-dashboard', icon: '📊' },
  { key: 'admin-stores', icon: '🏪' },
  { key: 'admin-add-store', icon: '➕' },
  { key: 'admin-offers', icon: '🏷️' },
  { key: 'admin-transactions', icon: '💱' },
  { key: 'admin-users', icon: '👥' },
  { key: 'admin-payouts', icon: '💸' },
  { key: 'admin-reports', icon: '📈' },
];

export default function AdminLayout({ children }: { children: ReactNode }) {
  const { dark, lang, page, setPage } = useApp();
  const tr = t(lang).admin;

  const labels: Record<AdminPage, string> = {
    'admin-dashboard': tr.dashboard,
    'admin-stores': tr.stores,
    'admin-add-store': tr.addStore,
    'admin-offers': tr.offers,
    'admin-transactions': tr.transactions,
    'admin-users': tr.users,
    'admin-payouts': tr.payouts,
    'admin-reports': tr.reports,
  };

  return (
    <div className={`flex h-screen overflow-hidden ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Sidebar */}
      <aside className={`w-56 flex-shrink-0 flex flex-col border-r overflow-y-auto ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
        <div className={`px-4 h-14 flex items-center border-b ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
          <button onClick={() => setPage('home')} className="flex items-center gap-2">
            <span className="flex items-center justify-center w-7 h-7 bg-primary-700 rounded-lg text-white text-xs font-bold">CB</span>
            <span className={`font-display font-700 text-sm ${dark ? 'text-white' : 'text-slate-900'}`}>Admin</span>
          </button>
        </div>
        <nav className="flex-1 p-2 flex flex-col gap-0.5">
          {navItems.map(({ key, icon }) => (
            <button
              key={key}
              onClick={() => setPage(key)}
              className={`flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium w-full text-left transition-colors ${page === key ? 'bg-primary-700 text-white' : dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-100'}`}
            >
              <span className="text-base">{icon}</span>
              <span>{labels[key]}</span>
            </button>
          ))}
        </nav>
        <div className={`p-3 border-t ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
          <div className={`flex items-center gap-2 px-2 py-1.5`}>
            <div className="w-7 h-7 rounded-full bg-primary-700 flex items-center justify-center text-white text-xs font-bold">A</div>
            <div className="flex-1 min-w-0">
              <p className={`text-xs font-semibold truncate ${dark ? 'text-white' : 'text-slate-900'}`}>Admin User</p>
              <p className={`text-xs truncate ${dark ? 'text-slate-500' : 'text-slate-400'}`}>admin@cashbackhub.de</p>
            </div>
          </div>
        </div>
      </aside>

      {/* Main content */}
      <main className="flex-1 overflow-y-auto">
        {children}
      </main>
    </div>
  );
}
