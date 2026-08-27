import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { stores, offers } from '../data';
import OfferCard from '../components/OfferCard';

export default function SearchResults() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang);
  const [query, setQuery] = useState('fashion');
  const [activeTab, setActiveTab] = useState<'stores' | 'offers'>('stores');

  const matchedStores = stores.filter(s => s.name.toLowerCase().includes(query.toLowerCase()) || s.category.includes(query.toLowerCase()));
  const matchedOffers = offers.filter(o => o.storeName.toLowerCase().includes(query.toLowerCase()) || o.title.en.toLowerCase().includes(query.toLowerCase()));

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Search bar */}
      <div className={`py-6 border-b ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
        <div className="max-w-3xl mx-auto px-4 sm:px-6">
          <div className={`flex items-center gap-2 px-4 py-3 rounded-xl border-2 ${dark ? 'bg-slate-800 border-primary-600/50' : 'bg-white border-primary-300'}`}>
            <svg className="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input
              value={query}
              onChange={e => setQuery(e.target.value)}
              placeholder={tr.nav.search}
              className={`flex-1 text-sm outline-none bg-transparent ${dark ? 'text-white placeholder-slate-500' : 'text-slate-800 placeholder-slate-400'}`}
              autoFocus
            />
            {query && (
              <button onClick={() => setQuery('')} className={`p-1 rounded ${dark ? 'text-slate-400 hover:text-white' : 'text-slate-400 hover:text-slate-600'}`}>
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            )}
          </div>

          {query && (
            <p className={`text-sm mt-3 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
              {matchedStores.length + matchedOffers.length} {lang === 'en' ? 'results for' : 'Ergebnisse für'} <strong className={dark ? 'text-white' : 'text-slate-800'}>"{query}"</strong>
            </p>
          )}
        </div>
      </div>

      <div className="max-w-3xl mx-auto px-4 sm:px-6 py-6">
        {/* Tabs */}
        <div className={`flex gap-1 p-1 rounded-xl mb-6 w-fit ${dark ? 'bg-slate-800' : 'bg-slate-100'}`}>
          {(['stores', 'offers'] as const).map(tab => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              className={`px-4 py-2 rounded-lg text-sm font-semibold transition-colors ${activeTab === tab ? 'bg-white text-primary-700 shadow-sm dark:bg-slate-700 dark:text-primary-300' : dark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-700'}`}
            >
              {tab === 'stores' ? tr.nav.stores : lang === 'en' ? 'Offers' : 'Angebote'}
              <span className={`ml-1.5 text-xs px-1.5 py-0.5 rounded-full ${activeTab === tab ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' : dark ? 'bg-slate-700 text-slate-400' : 'bg-slate-200 text-slate-500'}`}>
                {tab === 'stores' ? matchedStores.length : matchedOffers.length}
              </span>
            </button>
          ))}
        </div>

        {activeTab === 'stores' ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {matchedStores.length > 0 ? matchedStores.map(store => (
              <button
                key={store.id}
                onClick={() => setPage('store-detail')}
                className={`flex items-center gap-4 p-4 rounded-xl border transition-card card-shadow-hover text-left ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200 hover:border-primary-200'}`}
              >
                <img src={store.logo} alt={store.name} className="w-14 h-14 rounded-xl object-cover flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <p className={`font-display font-700 text-base ${dark ? 'text-white' : 'text-slate-900'}`}>{store.name}</p>
                  <p className={`text-xs mt-0.5 truncate ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{store.description[lang]}</p>
                  <div className="flex items-center gap-2 mt-2">
                    <span className="text-sm font-bold text-cash-600">{store.cashbackRate}% cashback</span>
                    <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>· {store.offerCount} offers</span>
                  </div>
                </div>
              </button>
            )) : (
              <div className="col-span-2 text-center py-12">
                <div className="text-4xl mb-3">🏪</div>
                <p className={`font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.common.noResults}</p>
              </div>
            )}
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-4">
            {matchedOffers.length > 0 ? matchedOffers.map(offer => (
              <OfferCard key={offer.id} offer={offer} />
            )) : (
              <div className="text-center py-12">
                <div className="text-4xl mb-3">🏷️</div>
                <p className={`font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.common.noResults}</p>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
