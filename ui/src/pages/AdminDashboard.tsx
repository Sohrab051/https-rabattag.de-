import { useApp } from '../context';
import { t } from '../i18n';
import { adminKpis, chartData, stores } from '../data';
import AdminLayout from './AdminLayout';

function KpiCard({ title, value, change, prefix = '', suffix = '' }: { title: string; value: number; change: number; prefix?: string; suffix?: string }) {
  const { dark } = useApp();
  const isPositive = change > 0;
  return (
    <div className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
      <p className={`text-xs font-medium uppercase tracking-wide mb-2 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{title}</p>
      <p className={`font-display font-800 text-2xl ${dark ? 'text-white' : 'text-slate-900'}`}>
        {prefix}{typeof value === 'number' && value > 1000 ? value.toLocaleString() : value}{suffix}
      </p>
      <div className="flex items-center gap-1 mt-1.5">
        <span className={`flex items-center gap-0.5 text-xs font-semibold ${isPositive ? 'text-cash-600' : 'text-red-500'}`}>
          {isPositive ? '↑' : '↓'} {Math.abs(change)}%
        </span>
        <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>vs last month</span>
      </div>
    </div>
  );
}

function MiniBarChart() {
  const { dark } = useApp();
  const maxRevenue = Math.max(...chartData.map(d => d.revenue));
  return (
    <div className={`p-5 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
      <div className="flex items-center justify-between mb-4">
        <h3 className={`font-display font-700 text-base ${dark ? 'text-white' : 'text-slate-900'}`}>Revenue vs Cashback Paid</h3>
        <div className="flex gap-3 text-xs">
          <span className="flex items-center gap-1"><span className="w-2.5 h-2.5 rounded-sm bg-primary-600 inline-block" /> Revenue</span>
          <span className="flex items-center gap-1"><span className="w-2.5 h-2.5 rounded-sm bg-cash-500 inline-block" /> Cashback</span>
        </div>
      </div>
      <div className="flex items-end gap-2 h-32">
        {chartData.map(({ month, revenue, cashback }) => (
          <div key={month} className="flex-1 flex flex-col items-center gap-0.5">
            <div className="w-full flex gap-0.5 items-end" style={{ height: '100px' }}>
              <div
                className="flex-1 bg-primary-600 rounded-t"
                style={{ height: `${(revenue / maxRevenue) * 100}%` }}
              />
              <div
                className="flex-1 bg-cash-500 rounded-t"
                style={{ height: `${(cashback / maxRevenue) * 100}%` }}
              />
            </div>
            <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{month}</span>
          </div>
        ))}
      </div>
    </div>
  );
}

export default function AdminDashboard() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang).admin;

  return (
    <AdminLayout>
      <div className="p-6">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className={`font-display font-800 text-2xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.dashboard}</h1>
            <p className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
              {lang === 'en' ? 'Overview for November 2024' : 'Übersicht für November 2024'}
            </p>
          </div>
          <button className={`flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium ${dark ? 'border-slate-700 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
            {lang === 'en' ? 'Export' : 'Exportieren'}
          </button>
        </div>

        {/* KPI row */}
        <div className="grid grid-cols-2 xl:grid-cols-5 gap-3 mb-6">
          <KpiCard title={tr.kpiClicks} value={adminKpis.clicks.value} change={adminKpis.clicks.change} />
          <KpiCard title={tr.kpiConversion} value={adminKpis.conversion.value} change={adminKpis.conversion.change} suffix="%" />
          <KpiCard title={tr.kpiRevenue} value={adminKpis.revenue.value} change={adminKpis.revenue.change} prefix="€" />
          <KpiCard title={tr.kpiPaid} value={adminKpis.cashbackPaid.value} change={adminKpis.cashbackPaid.change} prefix="€" />
          <KpiCard title={tr.kpiProfit} value={adminKpis.netProfit.value} change={adminKpis.netProfit.change} prefix="€" />
        </div>

        {/* Chart + top stores */}
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">
          <div className="lg:col-span-3">
            <MiniBarChart />
          </div>
          <div className={`lg:col-span-2 p-5 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
            <h3 className={`font-display font-700 text-base mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>
              {lang === 'en' ? 'Top Performing Stores' : 'Top-Shops'}
            </h3>
            <div className="flex flex-col gap-2">
              {stores.slice(0, 5).map((store, i) => (
                <div key={store.id} className="flex items-center gap-3">
                  <span className={`w-5 text-center text-xs font-bold ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{i+1}</span>
                  <img src={store.logo} alt={store.name} className="w-7 h-7 rounded-lg object-cover" />
                  <div className="flex-1 min-w-0">
                    <p className={`text-sm font-medium truncate ${dark ? 'text-white' : 'text-slate-800'}`}>{store.name}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-xs font-bold text-cash-600">{store.cashbackRate}%</p>
                    <p className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{store.offerCount} offers</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Quick actions */}
        <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
          {[
            { label: lang === 'en' ? 'Add Store' : 'Shop hinzufügen', icon: '🏪', page: 'admin-add-store' as const },
            { label: lang === 'en' ? 'Pending Payouts' : 'Ausstehende Ausz.', icon: '💸', page: 'admin-payouts' as const },
            { label: lang === 'en' ? 'Flagged Transactions' : 'Markierte Trans.', icon: '🚩', page: 'admin-transactions' as const },
            { label: lang === 'en' ? 'View Reports' : 'Berichte anzeigen', icon: '📈', page: 'admin-reports' as const },
          ].map(({ label, icon, page }) => (
            <button
              key={label}
              onClick={() => setPage(page)}
              className={`flex items-center gap-2 p-3 rounded-xl border text-sm font-medium text-left transition-colors ${dark ? 'bg-slate-800 border-slate-700/60 text-slate-300 hover:border-primary-600/50' : 'bg-white border-slate-200 text-slate-700 hover:border-primary-200'}`}
            >
              <span className="text-lg">{icon}</span>
              <span>{label}</span>
            </button>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
