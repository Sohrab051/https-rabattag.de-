import { useApp } from '../context';
import { t } from '../i18n';
import { stores, offers } from '../data';
import OfferCard from '../components/OfferCard';

const store = stores[0]; // Zalando as demo
const storeOffers = offers.filter(o => o.storeId === store.id);

const reviews = [
  { name: 'Julia M.', rating: 5, date: '2024-11-15', comment: { en: 'Cashback tracked perfectly within 48 hours. Will use again!', de: 'Cashback perfekt innerhalb von 48 Stunden erfasst. Nutze ich wieder!' } },
  { name: 'Thomas K.', rating: 4, date: '2024-11-08', comment: { en: 'Great cashback rate. Had one order not track but support fixed it quickly.', de: 'Super Cashback-Rate. Eine Bestellung wurde nicht erfasst, aber der Support hat es schnell behoben.' } },
  { name: 'Maria S.', rating: 5, date: '2024-10-30', comment: { en: 'The exclusive code saved me €28 extra on top of cashback. Amazing!', de: 'Der exklusive Code hat mir zusätzlich zum Cashback 28 € gespart. Toll!' } },
];

export default function StoreDetail() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang);

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Breadcrumb */}
      <div className={`py-3 border-b ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 flex items-center gap-2 text-sm">
          <button onClick={() => setPage('home')} className="text-primary-600 hover:underline">{tr.nav.home}</button>
          <span className={dark ? 'text-slate-500' : 'text-slate-400'}>/</span>
          <button onClick={() => setPage('stores')} className="text-primary-600 hover:underline">{tr.nav.stores}</button>
          <span className={dark ? 'text-slate-500' : 'text-slate-400'}>/</span>
          <span className={dark ? 'text-slate-300' : 'text-slate-600'}>{store.name}</span>
        </div>
      </div>

      {/* Store hero */}
      <div className={`py-8 ${dark ? 'bg-slate-900' : 'bg-white'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <img src={store.logo} alt={store.name} className="w-20 h-20 rounded-2xl object-cover shadow-lg" />
            <div className="flex-1">
              <div className="flex items-center gap-3 flex-wrap mb-1">
                <h1 className={`font-display font-800 text-2xl sm:text-3xl ${dark ? 'text-white' : 'text-slate-900'}`}>{store.name}</h1>
                <span className={`text-xs px-2 py-1 rounded-full font-semibold bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400`}>
                  ✓ {tr.offer.verified}
                </span>
              </div>
              <p className={`text-sm mb-3 ${dark ? 'text-slate-400' : 'text-slate-600'}`}>{store.description[lang]}</p>
              <div className="flex items-center gap-4 flex-wrap">
                <div className="flex items-center gap-1">
                  {[1,2,3,4,5].map(i => (
                    <svg key={i} className={`w-4 h-4 ${i <= Math.round(store.rating) ? 'text-amber-400' : dark ? 'text-slate-600' : 'text-slate-200'}`} fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                  ))}
                  <span className={`text-sm ml-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{store.rating} ({store.reviewCount.toLocaleString()})</span>
                </div>
                <span className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.store.since} {store.since}</span>
                <span className="text-sm text-primary-600 font-medium">{tr.store.offerCount(store.offerCount)}</span>
              </div>
            </div>

            {/* Big cashback badge */}
            <div className={`flex-shrink-0 flex flex-col items-center justify-center p-5 rounded-2xl ${dark ? 'bg-cash-900/30 border border-cash-700/40' : 'bg-cash-50 border border-cash-200'}`}>
              <span className={`text-xs font-semibold uppercase tracking-wider mb-1 ${dark ? 'text-cash-400' : 'text-cash-600'}`}>{tr.store.upTo}</span>
              <span className="text-4xl font-display font-900 text-cash-600">{store.cashbackRate}%</span>
              <span className={`text-xs font-semibold mt-1 ${dark ? 'text-cash-400' : 'text-cash-600'}`}>{tr.store.cashbackRate}</span>
            </div>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main: offers */}
          <div className="lg:col-span-2">
            <h2 className={`font-display font-700 text-xl mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.store.allOffers}</h2>
            <div className="flex flex-col gap-4">
              {storeOffers.length > 0 ? storeOffers.map(offer => (
                <OfferCard key={offer.id} offer={offer} />
              )) : (
                offers.slice(0, 3).map(offer => <OfferCard key={offer.id} offer={offer} />)
              )}
            </div>

            {/* How it works */}
            <div className={`mt-8 p-5 rounded-2xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
              <h3 className={`font-display font-700 text-lg mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.store.howItWorks}</h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {[tr.store.step1, tr.store.step2, tr.store.step3, tr.store.step4].map((step, i) => (
                  <div key={i} className="flex items-start gap-3">
                    <span className="flex-shrink-0 w-6 h-6 rounded-full bg-primary-700 text-white text-xs font-bold flex items-center justify-center">{i+1}</span>
                    <p className={`text-sm ${dark ? 'text-slate-300' : 'text-slate-600'}`}>{step}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Sidebar: reviews + related */}
          <div>
            {/* Reviews */}
            <h3 className={`font-display font-700 text-lg mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.store.reviews}</h3>
            <div className="flex flex-col gap-3 mb-8">
              {reviews.map((r, i) => (
                <div key={i} className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                  <div className="flex items-center justify-between mb-2">
                    <span className={`text-sm font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{r.name}</span>
                    <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{r.date}</span>
                  </div>
                  <div className="flex gap-0.5 mb-2">
                    {[1,2,3,4,5].map(i => (
                      <svg key={i} className={`w-3.5 h-3.5 ${i <= r.rating ? 'text-amber-400' : dark ? 'text-slate-600' : 'text-slate-200'}`} fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    ))}
                  </div>
                  <p className={`text-xs leading-relaxed ${dark ? 'text-slate-400' : 'text-slate-600'}`}>{r.comment[lang]}</p>
                </div>
              ))}
            </div>

            {/* Related stores */}
            <h3 className={`font-display font-700 text-lg mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.store.relatedStores}</h3>
            <div className="flex flex-col gap-2">
              {stores.filter(s => s.category === store.category && s.id !== store.id).concat(stores.filter(s => s.category !== store.category)).slice(0, 4).map(s => (
                <button key={s.id} onClick={() => setPage('store-detail')} className={`flex items-center gap-3 p-3 rounded-xl border transition-colors ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200 hover:border-primary-200'}`}>
                  <img src={s.logo} alt={s.name} className="w-9 h-9 rounded-lg object-cover" />
                  <div className="flex-1 text-left">
                    <p className={`text-sm font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{s.name}</p>
                  </div>
                  <span className="text-xs font-bold text-cash-600">{s.cashbackRate}%</span>
                </button>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
