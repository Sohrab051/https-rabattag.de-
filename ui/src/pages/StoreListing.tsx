import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { stores, categories } from '../data';

export default function StoreListing() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang);
  const [activeCategory, setActiveCategory] = useState<string>('all');
  const [search, setSearch] = useState('');
  const [sortBy, setSortBy] = useState<'cashback' | 'rating' | 'name'>('cashback');

  const filtered = stores
    .filter(s => activeCategory === 'all' || s.category === activeCategory)
    .filter(s => s.name.toLowerCase().includes(search.toLowerCase()))
    .sort((a, b) => {
      if (sortBy === 'cashback') return b.cashbackRate - a.cashbackRate;
      if (sortBy === 'rating') return b.rating - a.rating;
      return a.name.localeCompare(b.name);
    });

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Page header */}
      <div className={`py-8 border-b ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <h1 className={`font-display font-800 text-2xl sm:text-3xl mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>
            {tr.nav.stores}
          </h1>
          {/* Search */}
          <div className={`flex items-center gap-2 px-4 py-2.5 rounded-xl border mb-4 ${dark ? 'bg-slate-800 border-slate-700' : 'bg-slate-50 border-slate-200'}`}>
            <svg className="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder={tr.nav.search}
              className={`flex-1 text-sm outline-none bg-transparent ${dark ? 'text-white placeholder-slate-500' : 'text-slate-800 placeholder-slate-400'}`}
            />
          </div>

          {/* Category chips */}
          <div className="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
            <button
              onClick={() => setActiveCategory('all')}
              className={`flex-shrink-0 px-3 py-1.5 rounded-full text-sm font-medium transition-colors ${activeCategory === 'all' ? 'bg-primary-700 text-white' : dark ? 'bg-slate-800 text-slate-300 border border-slate-700' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'}`}
            >
              {tr.common.all}
            </button>
            {categories.map(cat => (
              <button
                key={cat.id}
                onClick={() => setActiveCategory(cat.id)}
                className={`flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition-colors ${activeCategory === cat.id ? 'bg-primary-700 text-white' : dark ? 'bg-slate-800 text-slate-300 border border-slate-700' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'}`}
              >
                <span>{cat.icon}</span>
                <span>{cat.label[lang]}</span>
              </button>
            ))}
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        {/* Sort + count */}
        <div className="flex items-center justify-between mb-5">
          <span className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
            {filtered.length} {lang === 'en' ? 'stores' : 'Shops'}
          </span>
          <select
            value={sortBy}
            onChange={e => setSortBy(e.target.value as typeof sortBy)}
            className={`text-sm px-3 py-1.5 rounded-lg border outline-none ${dark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-700'}`}
          >
            <option value="cashback">{tr.common.sortBy}: Cashback</option>
            <option value="rating">{tr.common.sortBy}: Rating</option>
            <option value="name">{tr.common.sortBy}: Name</option>
          </select>
        </div>

        {/* Store grid */}
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
          {filtered.map(store => (
            <button
              key={store.id}
              onClick={() => setPage('store-detail')}
              className={`group flex flex-col items-center gap-3 p-4 rounded-xl border transition-card card-shadow-hover text-left ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200/80 hover:border-primary-200'}`}
            >
              <img src={store.logo} alt={store.name} className="w-14 h-14 rounded-xl object-cover" />
              <div className="text-center">
                <p className={`font-display font-600 text-sm ${dark ? 'text-white' : 'text-slate-900'}`}>{store.name}</p>
                <p className={`text-xs mt-0.5 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{store.offerCount} {lang === 'en' ? 'offers' : 'Angebote'}</p>
              </div>
              <div className="flex flex-col items-center gap-1">
                <span className="flex items-center gap-1 px-2.5 py-1 rounded-full bg-cash-600 text-white text-xs font-bold">
                  {store.cashbackRate}{store.cashbackType === 'percent' ? '%' : '€'} {lang === 'en' ? 'cashback' : 'Cashback'}
                </span>
                <div className="flex items-center gap-0.5">
                  {[1,2,3,4,5].map(i => (
                    <svg key={i} className={`w-3 h-3 ${i <= Math.round(store.rating) ? 'text-amber-400' : dark ? 'text-slate-600' : 'text-slate-200'}`} fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                  ))}
                  <span className={`text-xs ml-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{store.rating}</span>
                </div>
              </div>
            </button>
          ))}
        </div>

        {filtered.length === 0 && (
          <div className="text-center py-16">
            <div className="text-5xl mb-4">🔍</div>
            <p className={`font-display font-600 text-lg mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.common.noResults}</p>
            <p className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.common.tryAgain}</p>
          </div>
        )}
      </div>
    </div>
  );
}
