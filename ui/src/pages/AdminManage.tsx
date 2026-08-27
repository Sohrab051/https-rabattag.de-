import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { stores, offers, adminTransactions, adminUsers, payoutRequests } from '../data';
import AdminLayout from './AdminLayout';

type StatusKey = 'draft' | 'pending' | 'published' | 'expired' | 'approved' | 'rejected' | 'flagged' | 'active' | 'blocked';

const statusBadge = (status: string, dark: boolean) => {
  const map: Record<string, string> = {
    published: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400',
    approved: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400',
    active: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400',
    pending: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    draft: 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400',
    expired: 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-500',
    rejected: 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    blocked: 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    flagged: 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
  };
  return `text-xs px-1.5 py-0.5 rounded-full font-semibold ${map[status] || map.draft}`;
};

function Th({ children }: { children: React.ReactNode }) {
  const { dark } = useApp();
  return <th className={`px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wide ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{children}</th>;
}

function ActionBtn({ children, variant = 'default', onClick }: { children: React.ReactNode; variant?: 'default' | 'danger' | 'success'; onClick?: () => void }) {
  const { dark } = useApp();
  const cls = variant === 'danger' ? 'text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20' : variant === 'success' ? 'text-cash-600 hover:bg-cash-50 dark:hover:bg-cash-900/20' : dark ? 'text-slate-400 hover:bg-slate-700' : 'text-slate-500 hover:bg-slate-100';
  return <button onClick={onClick} className={`px-2 py-1 rounded-lg text-xs font-semibold transition-colors ${cls}`}>{children}</button>;
}

export default function AdminManage() {
  const { dark, lang, page, setPage, showToast } = useApp();
  const tr = t(lang).admin;
  const [storeSearch, setStoreSearch] = useState('');
  const [offerFilter, setOfferFilter] = useState('all');
  const [txFilter, setTxFilter] = useState('all');

  const tableClasses = `w-full ${dark ? 'bg-slate-800' : 'bg-white'}`;
  const rowClasses = (i: number) => `${i > 0 ? (dark ? 'border-t border-slate-700/60' : 'border-t border-slate-100') : ''}`;
  const tdClasses = `px-3 py-2.5 text-sm ${dark ? 'text-slate-200' : 'text-slate-700'}`;
  const theadClasses = `${dark ? 'bg-slate-900/50 border-b border-slate-700/60' : 'bg-slate-50 border-b border-slate-200'}`;

  return (
    <AdminLayout>
      <div className="p-6">
        {page === 'admin-stores' && (
          <div>
            <div className="flex items-center justify-between mb-5">
              <h1 className={`font-display font-800 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.stores}</h1>
              <button onClick={() => setPage('admin-add-store')} className="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors">
                <span>+</span> {tr.addStore}
              </button>
            </div>
            <div className={`flex items-center gap-2 px-3 py-2 rounded-lg border mb-4 ${dark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
              <svg className="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
              <input value={storeSearch} onChange={e => setStoreSearch(e.target.value)} placeholder="Search stores…" className={`flex-1 text-sm outline-none bg-transparent ${dark ? 'text-white placeholder-slate-500' : 'text-slate-800 placeholder-slate-400'}`} />
            </div>
            <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>Store</Th><Th>Category</Th><Th>Cashback</Th><Th>Offers</Th><Th>Rating</Th><Th>Status</Th><Th>Actions</Th></tr>
                </thead>
                <tbody>
                  {stores.filter(s => s.name.toLowerCase().includes(storeSearch.toLowerCase())).map((store, i) => (
                    <tr key={store.id} className={rowClasses(i)}>
                      <td className={tdClasses}>
                        <div className="flex items-center gap-2">
                          <img src={store.logo} alt={store.name} className="w-8 h-8 rounded-lg object-cover" />
                          <span className="font-medium">{store.name}</span>
                        </div>
                      </td>
                      <td className={tdClasses}>{store.category}</td>
                      <td className={tdClasses}><span className="font-semibold text-cash-600">{store.cashbackRate}%</span></td>
                      <td className={tdClasses}>{store.offerCount}</td>
                      <td className={tdClasses}>⭐ {store.rating}</td>
                      <td className={tdClasses}><span className={statusBadge('active', dark)}>active</span></td>
                      <td className={tdClasses}>
                        <div className="flex gap-1">
                          <ActionBtn onClick={() => showToast('Store edited', 'info')}>{t(lang).common.edit}</ActionBtn>
                          <ActionBtn variant="danger" onClick={() => showToast('Store disabled', 'error')}>Disable</ActionBtn>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {page === 'admin-offers' && (
          <div>
            <div className="flex items-center justify-between mb-5">
              <h1 className={`font-display font-800 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.offers}</h1>
              <div className={`flex gap-1 p-1 rounded-lg ${dark ? 'bg-slate-800' : 'bg-slate-100'}`}>
                {['all', 'published', 'pending', 'draft', 'expired'].map(f => (
                  <button key={f} onClick={() => setOfferFilter(f)} className={`px-2.5 py-1 rounded-md text-xs font-semibold transition-colors ${offerFilter === f ? 'bg-white text-primary-700 shadow-sm dark:bg-slate-700 dark:text-white' : dark ? 'text-slate-400' : 'text-slate-500'}`}>
                    {f.charAt(0).toUpperCase() + f.slice(1)}
                  </button>
                ))}
              </div>
            </div>
            <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>Offer</Th><Th>Store</Th><Th>Type</Th><Th>Value</Th><Th>Expires</Th><Th>Clicks</Th><Th>Status</Th><Th>Actions</Th></tr>
                </thead>
                <tbody>
                  {offers.map((offer, i) => (
                    <tr key={offer.id} className={rowClasses(i)}>
                      <td className={tdClasses}><span className="font-medium line-clamp-1 max-w-[180px] block">{offer.title.en}</span></td>
                      <td className={tdClasses}>{offer.storeName}</td>
                      <td className={tdClasses}><span className="capitalize">{offer.type}</span></td>
                      <td className={tdClasses}><span className="font-semibold">{offer.discountValue}</span></td>
                      <td className={tdClasses}>{offer.expiresAt.toLocaleDateString()}</td>
                      <td className={tdClasses}>{offer.clicks.toLocaleString()}</td>
                      <td className={tdClasses}><span className={statusBadge('published', dark)}>published</span></td>
                      <td className={tdClasses}>
                        <div className="flex gap-1">
                          <ActionBtn>{t(lang).common.edit}</ActionBtn>
                          <ActionBtn variant="danger">{t(lang).common.delete}</ActionBtn>
                        </div>
                      </td>
                    </tr>
                  ))}
                  {/* Draft demo row */}
                  <tr className={rowClasses(offers.length)}>
                    <td className={tdClasses}><span className="font-medium">Summer Sale Preview</span></td>
                    <td className={tdClasses}>MediaMarkt</td>
                    <td className={tdClasses}>deal</td>
                    <td className={tdClasses}>25%</td>
                    <td className={tdClasses}>2024-12-24</td>
                    <td className={tdClasses}>—</td>
                    <td className={tdClasses}><span className={statusBadge('draft', dark)}>draft</span></td>
                    <td className={tdClasses}>
                      <div className="flex gap-1">
                        <ActionBtn>{t(lang).common.edit}</ActionBtn>
                        <ActionBtn variant="success">Publish</ActionBtn>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        )}

        {page === 'admin-transactions' && (
          <div>
            <div className="flex items-center justify-between mb-5">
              <h1 className={`font-display font-800 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.transactions}</h1>
              <div className={`flex gap-1 p-1 rounded-lg ${dark ? 'bg-slate-800' : 'bg-slate-100'}`}>
                {['all', 'pending', 'approved', 'rejected'].map(f => (
                  <button key={f} onClick={() => setTxFilter(f)} className={`px-2.5 py-1 rounded-md text-xs font-semibold transition-colors ${txFilter === f ? 'bg-white text-primary-700 shadow-sm dark:bg-slate-700 dark:text-white' : dark ? 'text-slate-400' : 'text-slate-500'}`}>
                    {f.charAt(0).toUpperCase() + f.slice(1)}
                  </button>
                ))}
              </div>
            </div>
            <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>User</Th><Th>Store</Th><Th>Amount</Th><Th>Commission</Th><Th>Cashback</Th><Th>Date</Th><Th>Status</Th><Th>Actions</Th></tr>
                </thead>
                <tbody>
                  {adminTransactions.map((tx, i) => (
                    <tr key={tx.id} className={rowClasses(i)}>
                      <td className={tdClasses}>
                        <div>
                          <p className="font-medium text-xs">{tx.user}</p>
                        </div>
                      </td>
                      <td className={tdClasses}>{tx.store}</td>
                      <td className={tdClasses}>€{tx.amount.toFixed(2)}</td>
                      <td className={tdClasses}><span className="font-semibold text-primary-600">€{tx.commission.toFixed(2)}</span></td>
                      <td className={tdClasses}><span className="font-semibold text-cash-600">€{tx.cashback.toFixed(2)}</span></td>
                      <td className={tdClasses}>{tx.date.toLocaleDateString()}</td>
                      <td className={tdClasses}>
                        <div className="flex items-center gap-1">
                          <span className={statusBadge(tx.status, dark)}>{tx.status}</span>
                          {tx.fraud && <span className="text-xs px-1.5 py-0.5 rounded-full font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">🚩 Fraud</span>}
                        </div>
                      </td>
                      <td className={tdClasses}>
                        {tx.status === 'pending' && (
                          <div className="flex gap-1">
                            <ActionBtn variant="success" onClick={() => showToast('Transaction approved', 'success')}>{tr.approve}</ActionBtn>
                            <ActionBtn variant="danger" onClick={() => showToast('Transaction rejected', 'error')}>{tr.reject}</ActionBtn>
                          </div>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {page === 'admin-users' && (
          <div>
            <h1 className={`font-display font-800 text-xl mb-5 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.users}</h1>
            <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>User</Th><Th>Country</Th><Th>Joined</Th><Th>Balance</Th><Th>Pending</Th><Th>Total Earned</Th><Th>Status</Th><Th>Actions</Th></tr>
                </thead>
                <tbody>
                  {adminUsers.map((user, i) => (
                    <tr key={user.id} className={rowClasses(i)}>
                      <td className={tdClasses}>
                        <div>
                          <p className="font-medium">{user.name}</p>
                          <p className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{user.email}</p>
                        </div>
                      </td>
                      <td className={tdClasses}>{user.country}</td>
                      <td className={tdClasses}>{user.joined.toLocaleDateString()}</td>
                      <td className={tdClasses}><span className="font-semibold text-cash-600">€{user.balance.toFixed(2)}</span></td>
                      <td className={tdClasses}><span className="text-amber-600">€{user.pending.toFixed(2)}</span></td>
                      <td className={tdClasses}>€{user.totalEarned.toFixed(2)}</td>
                      <td className={tdClasses}><span className={statusBadge(user.status, dark)}>{user.status}</span></td>
                      <td className={tdClasses}>
                        <div className="flex gap-1">
                          <ActionBtn>{t(lang).common.edit}</ActionBtn>
                          <ActionBtn variant="danger" onClick={() => showToast('User blocked', 'error')}>{tr.block}</ActionBtn>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {page === 'admin-payouts' && (
          <div>
            <h1 className={`font-display font-800 text-xl mb-5 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.payouts}</h1>
            <div className={`rounded-xl border overflow-hidden ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>User</Th><Th>Amount</Th><Th>Method</Th><Th>Account</Th><Th>Requested</Th><Th>Status</Th><Th>Actions</Th></tr>
                </thead>
                <tbody>
                  {payoutRequests.map((req, i) => (
                    <tr key={req.id} className={rowClasses(i)}>
                      <td className={tdClasses}>
                        <div>
                          <p className="font-medium">{req.user}</p>
                          <p className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{req.email}</p>
                        </div>
                      </td>
                      <td className={tdClasses}><span className="font-semibold">€{req.amount.toFixed(2)}</span></td>
                      <td className={tdClasses}>{req.method}</td>
                      <td className={tdClasses}><span className="font-mono text-xs">{req.account}</span></td>
                      <td className={tdClasses}>{req.requested.toLocaleDateString()}</td>
                      <td className={tdClasses}><span className={statusBadge(req.status, dark)}>{req.status}</span></td>
                      <td className={tdClasses}>
                        {req.status === 'pending' && (
                          <div className="flex gap-1">
                            <ActionBtn variant="success" onClick={() => showToast('Payout approved', 'success')}>{tr.approve}</ActionBtn>
                            <ActionBtn variant="danger" onClick={() => showToast('Payout rejected', 'error')}>{tr.reject}</ActionBtn>
                          </div>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {page === 'admin-reports' && (
          <div>
            <div className="flex items-center justify-between mb-5">
              <h1 className={`font-display font-800 text-xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.reports}</h1>
              <button className={`flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium ${dark ? 'border-slate-700 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                {t(lang).common.export} CSV
              </button>
            </div>

            {/* Monthly data */}
            <div className={`rounded-xl border overflow-hidden mb-6 ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
              <table className={tableClasses}>
                <thead className={theadClasses}>
                  <tr><Th>Month</Th><Th>Clicks</Th><Th>Revenue (€)</Th><Th>Cashback (€)</Th><Th>Net Profit (€)</Th><Th>Margin</Th></tr>
                </thead>
                <tbody>
                  {[...chartData].reverse().map((row, i) => {
                    const profit = row.revenue - row.cashback;
                    const margin = ((profit / row.revenue) * 100).toFixed(1);
                    return (
                      <tr key={row.month} className={rowClasses(i)}>
                        <td className={`${tdClasses} font-semibold`}>{row.month} 2024</td>
                        <td className={tdClasses}>{row.clicks.toLocaleString()}</td>
                        <td className={tdClasses}><span className="font-semibold">€{row.revenue.toLocaleString()}</span></td>
                        <td className={tdClasses}><span className="text-cash-600">€{row.cashback.toLocaleString()}</span></td>
                        <td className={tdClasses}><span className="font-semibold text-primary-600">€{profit.toLocaleString()}</span></td>
                        <td className={tdClasses}>{margin}%</td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>

            {/* Simple bar chart */}
            <div className={`p-5 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
              <h3 className={`font-display font-700 text-base mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>Monthly Clicks Trend</h3>
              <div className="flex items-end gap-3 h-28">
                {chartData.map(({ month, clicks }) => {
                  const max = Math.max(...chartData.map(d => d.clicks));
                  const pct = (clicks / max) * 100;
                  return (
                    <div key={month} className="flex-1 flex flex-col items-center gap-1">
                      <span className={`text-xs font-semibold ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{(clicks/1000).toFixed(0)}k</span>
                      <div className="w-full rounded-t bg-primary-600 hover:bg-primary-500 transition-colors cursor-pointer" style={{ height: `${pct * 0.8}px` }} />
                      <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{month}</span>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        )}
      </div>
    </AdminLayout>
  );
}
