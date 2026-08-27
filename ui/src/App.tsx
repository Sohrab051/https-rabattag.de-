import { AppProvider, useApp } from './context';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import CookieBanner from './components/CookieBanner';
import DesignSystem from './pages/DesignSystem';
import HomePage from './pages/HomePage';
import StoreListing from './pages/StoreListing';
import StoreDetail from './pages/StoreDetail';
import SearchResults from './pages/SearchResults';
import AuthPage from './pages/AuthPage';
import UserDashboard from './pages/UserDashboard';
import WithdrawalPage from './pages/WithdrawalPage';
import BlogPage from './pages/BlogPage';
import AdminDashboard from './pages/AdminDashboard';
import AdminAddStore from './pages/AdminAddStore';
import AdminManage from './pages/AdminManage';

// Prototype navigation — all screens accessible in one place
const NAV_SECTIONS = [
  {
    label: '🎨 System',
    items: [{ key: 'design-system', label: 'Design System' }],
  },
  {
    label: '🌐 Public',
    items: [
      { key: 'home', label: 'Homepage' },
      { key: 'stores', label: 'Store Listing' },
      { key: 'store-detail', label: 'Store Detail' },
      { key: 'search', label: 'Search' },
      { key: 'blog', label: 'Blog' },
      { key: 'auth-signin', label: 'Sign In' },
      { key: 'auth-signup', label: 'Sign Up' },
    ],
  },
  {
    label: '👤 User',
    items: [
      { key: 'dashboard', label: 'Dashboard' },
      { key: 'withdrawal', label: 'Withdrawal' },
    ],
  },
  {
    label: '⚙️ Admin',
    items: [
      { key: 'admin-dashboard', label: 'Admin Dashboard' },
      { key: 'admin-add-store', label: 'Add Store Wizard' },
      { key: 'admin-stores', label: 'Stores Table' },
      { key: 'admin-offers', label: 'Offers Table' },
      { key: 'admin-transactions', label: 'Transactions' },
      { key: 'admin-users', label: 'Users' },
      { key: 'admin-payouts', label: 'Payouts' },
      { key: 'admin-reports', label: 'Reports' },
    ],
  },
] as const;

type PageKey = typeof NAV_SECTIONS[number]['items'][number]['key'];

function Toast() {
  const { toast, dark } = useApp();
  if (!toast) return null;
  const bg = toast.type === 'success' ? 'bg-cash-600' : toast.type === 'error' ? 'bg-red-600' : 'bg-primary-700';
  return (
    <div className={`fixed top-20 right-4 z-[100] flex items-center gap-2 px-4 py-3 rounded-xl shadow-xl text-white text-sm font-semibold ${bg}`}>
      {toast.type === 'success' ? '✓' : toast.type === 'error' ? '✗' : 'ℹ'}
      {toast.msg}
    </div>
  );
}

function ProtoNav() {
  const { page, setPage, dark } = useApp();
  const [open, setOpen] = useState(false);
  return (
    <div className="relative z-[200]">
      <button
        onClick={() => setOpen(!open)}
        className="fixed bottom-4 right-4 flex items-center gap-2 px-3 py-2 rounded-full shadow-lg text-xs font-semibold bg-slate-900 text-white border border-slate-700"
      >
        <span>🗂</span>
        <span>All Screens</span>
      </button>
      {open && (
        <div className={`fixed bottom-14 right-4 w-56 rounded-2xl shadow-2xl border overflow-y-auto max-h-[70vh] ${dark ? 'bg-slate-900 border-slate-700' : 'bg-white border-slate-200'}`}>
          <div className={`px-3 py-2 border-b ${dark ? 'border-slate-700' : 'border-slate-100'}`}>
            <p className={`text-xs font-bold ${dark ? 'text-slate-400' : 'text-slate-500'}`}>PROTOTYPE SCREENS</p>
          </div>
          {NAV_SECTIONS.map(({ label, items }) => (
            <div key={label}>
              <p className={`px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wider ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{label}</p>
              {items.map(({ key, label: itemLabel }) => (
                <button
                  key={key}
                  onClick={() => { setPage(key as PageKey); setOpen(false); }}
                  className={`w-full text-left px-3 py-1.5 text-xs font-medium transition-colors ${page === key ? 'bg-primary-700 text-white' : dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-slate-50'}`}
                >
                  {itemLabel}
                </button>
              ))}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

import { useState } from 'react';

function AppShell() {
  const { page, dark } = useApp();
  const isAdmin = page.startsWith('admin');
  const isAuth = page.startsWith('auth');

  const renderPage = () => {
    switch (page) {
      case 'design-system': return <DesignSystem />;
      case 'home': return <HomePage />;
      case 'stores': return <StoreListing />;
      case 'store-detail': return <StoreDetail />;
      case 'search': return <SearchResults />;
      case 'auth-signin': case 'auth-signup': return <AuthPage />;
      case 'dashboard': return <UserDashboard />;
      case 'withdrawal': return <WithdrawalPage />;
      case 'blog': case 'blog-article': return <BlogPage />;
      case 'admin-dashboard': return <AdminDashboard />;
      case 'admin-add-store': return <AdminAddStore />;
      case 'admin-stores':
      case 'admin-offers':
      case 'admin-transactions':
      case 'admin-users':
      case 'admin-payouts':
      case 'admin-reports':
        return <AdminManage />;
      default: return <HomePage />;
    }
  };

  return (
    <div className={`min-h-screen flex flex-col ${dark ? 'dark' : ''}`}>
      <Toast />
      {!isAdmin && !isAuth && <Navbar />}
      <div className="flex-1">
        {renderPage()}
      </div>
      {!isAdmin && !isAuth && <Footer />}
      <CookieBanner />
      <ProtoNav />
    </div>
  );
}

export default function App() {
  return (
    <AppProvider>
      <AppShell />
    </AppProvider>
  );
}
