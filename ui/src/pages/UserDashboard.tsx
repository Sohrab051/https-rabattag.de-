import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { transactions, stores } from '../data';

const statusColors = {
  pending: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
  approved: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400',
  paid: 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300',
  rejected: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
};

export default function UserDashboard() {
  const { dark, lang, setPage, showToast } = useApp();
  const tr = t(lang);
  const [activeTab, setActiveTab] = useState<'wallet' | 'transactions' | 'saved' | 'referral' | 'settings'>('wallet');
  const [copied, setCopied] = useState(false);

  const pending = transactions.filter(t => t.status === 'pending').reduce((s, t) => s + t.cashbackAmount, 0);
  const available = transactions.filter(t => t.status === 'approved' || t.status === 'paid').reduce((s, t) => s + t.cashbackAmount, 0);
  const referralLink = 'https://cashbackhub.de/ref/ABC123DE';

  const tabs = [
    { key: 'wallet', label: tr.dashboard.wallet, icon: '💳' },
    { key: 'transactions', label: tr.dashboard.transactions, icon: '📋' },
    { key: 'saved', label: tr.dashboard.savedStores, icon: '❤️' },
    { key: 'referral', label: tr.dashboard.referral, icon: '🎁' },
    { key: 'settings', label: tr.dashboard.settings, icon: '⚙️' },
  ] as const;

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Header */}
      <div className={`py-6 border-b ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
        <div className="max-w-5xl mx-auto px-4 sm:px-6">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-full bg-primary-700 flex items-center justify-center text-white font-bold font-display text-lg">JM</div>
            <div>
              <h1 className={`font-display font-800 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>Jan Müller</h1>
              <p className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>jan.mueller@gmail.com · {lang === 'en' ? 'Member since' : 'Mitglied seit'} Jan 2024</p>
            </div>
          </div>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 py-6">
        <div className="flex flex-col lg:flex-row gap-6">
          {/* Sidebar tabs */}
          <div className={`lg:w-52 flex-shrink-0`}>
            <nav className={`flex flex-row lg:flex-col gap-1 overflow-x-auto pb-1 lg:pb-0 ${dark ? '' : ''}`}>
              {tabs.map(({ key, label, icon }) => (
                <button
                  key={key}
                  onClick={() => setActiveTab(key)}
                  className={`flex-shrink-0 flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors whitespace-nowrap ${activeTab === key ? 'bg-primary-700 text-white' : dark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-600 hover:bg-white hover:shadow-sm'}`}
                >
                  <span>{icon}</span>
                  <span>{label}</span>
                </button>
              ))}
            </nav>
          </div>

          {/* Main content */}
          <div className="flex-1 min-w-0">
            {activeTab === 'wallet' && (
              <div>
                {/* Wallet card */}
                <div className="rounded-2xl bg-gradient-to-br from-primary-700 to-primary-800 p-6 mb-6 text-white">
                  <p className="text-sm text-white/70 mb-1">{tr.dashboard.wallet}</p>
                  <p className="font-display font-900 text-4xl mb-4">€{(pending + available).toFixed(2)}</p>
                  <div className="flex gap-4">
                    <div>
                      <p className="text-xs text-white/60">{tr.dashboard.available}</p>
                      <p className="font-display font-700 text-xl text-cash-300">€{available.toFixed(2)}</p>
                    </div>
                    <div className={`w-px ${dark ? 'bg-white/20' : 'bg-white/20'}`} />
                    <div>
                      <p className="text-xs text-white/60">{tr.dashboard.pending}</p>
                      <p className="font-display font-700 text-xl text-amber-300">€{pending.toFixed(2)}</p>
                    </div>
                  </div>
                  <button
                    onClick={() => setPage('withdrawal')}
                    className="mt-5 flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl transition-colors"
                  >
                    {tr.dashboard.withdraw}
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                  </button>
                </div>

                {/* Quick stats */}
                <div className="grid grid-cols-3 gap-3 mb-6">
                  {[
                    { label: lang === 'en' ? 'Total Earned' : 'Gesamt verdient', value: '€156.40' },
                    { label: lang === 'en' ? 'Transactions' : 'Transaktionen', value: '24' },
                    { label: lang === 'en' ? 'Saved Stores' : 'Gespeicherte Shops', value: '8' },
                  ].map(({ label, value }) => (
                    <div key={label} className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                      <p className={`text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{label}</p>
                      <p className={`font-display font-700 text-lg mt-0.5 ${dark ? 'text-white' : 'text-slate-900'}`}>{value}</p>
                    </div>
                  ))}
                </div>

                {/* Skeleton loading state demo */}
                <h3 className={`font-display font-700 text-base mb-3 ${dark ? 'text-white' : 'text-slate-900'}`}>{lang === 'en' ? 'Recent Activity' : 'Letzte Aktivität'}</h3>
                <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
                  {transactions.slice(0, 4).map((tx, i) => (
                    <div key={tx.id} className={`flex items-center gap-3 px-4 py-3 ${i > 0 ? (dark ? 'border-t border-slate-700/60' : 'border-t border-slate-100') : ''} ${dark ? 'bg-slate-800' : 'bg-white'}`}>
                      <img src={tx.storeLogo} alt={tx.storeName} className="w-9 h-9 rounded-lg object-cover flex-shrink-0" />
                      <div className="flex-1 min-w-0">
                        <p className={`text-sm font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{tx.storeName}</p>
                        <p className={`text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>#{tx.orderId}</p>
                      </div>
                      <div className="text-right">
                        <p className="text-sm font-bold text-cash-600">+€{tx.cashbackAmount.toFixed(2)}</p>
                        <span className={`text-xs px-1.5 py-0.5 rounded-full font-medium ${statusColors[tx.status]}`}>{lang === 'en' ? tx.status : { pending: 'Ausstehend', approved: 'Genehmigt', paid: 'Ausgezahlt', rejected: 'Abgelehnt' }[tx.status]}</span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeTab === 'transactions' && (
              <div>
                <h2 className={`font-display font-700 text-xl mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.dashboard.transactions}</h2>
                <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
                  <div className={`grid grid-cols-5 px-4 py-2.5 text-xs font-semibold uppercase tracking-wider ${dark ? 'bg-slate-800/50 text-slate-400 border-b border-slate-700/60' : 'bg-slate-50 text-slate-500 border-b border-slate-200'}`}>
                    <span className="col-span-2">{lang === 'en' ? 'Store' : 'Shop'}</span>
                    <span className="text-right">{lang === 'en' ? 'Order' : 'Betrag'}</span>
                    <span className="text-right">{lang === 'en' ? 'Cashback' : 'Cashback'}</span>
                    <span className="text-right">{lang === 'en' ? 'Status' : 'Status'}</span>
                  </div>
                  {transactions.map((tx, i) => (
                    <div key={tx.id} className={`grid grid-cols-5 items-center px-4 py-3 ${i > 0 ? (dark ? 'border-t border-slate-700/60' : 'border-t border-slate-100') : ''} ${dark ? 'bg-slate-800' : 'bg-white'}`}>
                      <div className="col-span-2 flex items-center gap-2 min-w-0">
                        <img src={tx.storeLogo} alt={tx.storeName} className="w-8 h-8 rounded-lg object-cover flex-shrink-0" />
                        <div className="min-w-0">
                          <p className={`text-sm font-medium truncate ${dark ? 'text-white' : 'text-slate-800'}`}>{tx.storeName}</p>
                          <p className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{tx.date.toLocaleDateString()}</p>
                        </div>
                      </div>
                      <p className={`text-sm text-right ${dark ? 'text-slate-300' : 'text-slate-600'}`}>€{tx.amount.toFixed(2)}</p>
                      <p className="text-sm font-semibold text-cash-600 text-right">+€{tx.cashbackAmount.toFixed(2)}</p>
                      <div className="flex justify-end">
                        <span className={`text-xs px-1.5 py-0.5 rounded-full font-medium ${statusColors[tx.status]}`}>
                          {lang === 'en' ? tx.status : { pending: 'Ausstehend', approved: 'Genehmigt', paid: 'Ausgezahlt', rejected: 'Abgelehnt' }[tx.status]}
                        </span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeTab === 'saved' && (
              <div>
                <h2 className={`font-display font-700 text-xl mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.dashboard.savedStores}</h2>
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                  {stores.slice(0, 6).map(store => (
                    <div key={store.id} className={`p-4 rounded-xl border flex flex-col items-center gap-2 ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                      <img src={store.logo} alt={store.name} className="w-12 h-12 rounded-xl object-cover" />
                      <p className={`text-sm font-semibold text-center ${dark ? 'text-white' : 'text-slate-900'}`}>{store.name}</p>
                      <span className="text-xs font-bold text-cash-600">{store.cashbackRate}% cashback</span>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeTab === 'referral' && (
              <div>
                <div className={`p-6 rounded-2xl border mb-5 ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                  <div className="text-3xl mb-3">🎁</div>
                  <h2 className={`font-display font-700 text-xl mb-1 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.dashboard.referralTitle}</h2>
                  <p className={`text-sm mb-5 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.dashboard.referralSubtitle}</p>
                  <div className={`flex gap-2 p-3 rounded-xl border ${dark ? 'bg-slate-900 border-slate-700 border-dashed' : 'bg-slate-50 border-slate-200 border-dashed'}`}>
                    <span className={`flex-1 text-sm font-mono truncate ${dark ? 'text-slate-300' : 'text-slate-600'}`}>{referralLink}</span>
                    <button
                      onClick={() => { navigator.clipboard.writeText(referralLink).catch(() => {}); setCopied(true); showToast('Link copied!', 'success'); setTimeout(() => setCopied(false), 2000); }}
                      className={`px-3 py-1 rounded-lg text-xs font-semibold transition-colors ${copied ? 'bg-cash-600 text-white' : 'bg-primary-700 text-white hover:bg-primary-800'}`}
                    >
                      {copied ? '✓' : tr.dashboard.copyLink}
                    </button>
                  </div>
                </div>
                <div className="grid grid-cols-3 gap-3">
                  {[
                    { label: lang === 'en' ? 'Friends Invited' : 'Eingeladene Freunde', value: '3' },
                    { label: lang === 'en' ? 'Joined' : 'Beigetreten', value: '2' },
                    { label: lang === 'en' ? 'Earned' : 'Verdient', value: '€20.00' },
                  ].map(({ label, value }) => (
                    <div key={label} className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                      <p className={`text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{label}</p>
                      <p className={`font-display font-700 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>{value}</p>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {activeTab === 'settings' && (
              <div className={`p-6 rounded-2xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                <h2 className={`font-display font-700 text-xl mb-5 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.dashboard.settings}</h2>
                <div className="flex flex-col gap-4">
                  {[
                    { label: tr.auth.firstName, value: 'Jan', type: 'text' },
                    { label: tr.auth.lastName, value: 'Müller', type: 'text' },
                    { label: tr.auth.email, value: 'jan.mueller@gmail.com', type: 'email' },
                  ].map(({ label, value, type }) => (
                    <div key={label}>
                      <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{label}</label>
                      <input defaultValue={value} type={type} className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
                    </div>
                  ))}
                  <div>
                    <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.dashboard.language}</label>
                    <select className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} defaultValue="de-EUR">
                      <option value="en-GBP">English · GBP</option>
                      <option value="de-EUR">Deutsch · EUR</option>
                      <option value="de-EUR-AT">Österreichisches Deutsch · EUR</option>
                    </select>
                  </div>
                  <button onClick={() => showToast(lang === 'en' ? 'Settings saved!' : 'Einstellungen gespeichert!', 'success')} className="px-4 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors w-fit">
                    {tr.common.save}
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
