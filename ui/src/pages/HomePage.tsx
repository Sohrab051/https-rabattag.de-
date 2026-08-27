import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { stores, offers, categories } from '../data';
import OfferCard from '../components/OfferCard';

function StoreTile({ store, onClick }: { store: typeof stores[0], onClick: () => void }) {
  const { dark, lang } = useApp();
  return (
    <button
      onClick={onClick}
      className={`group flex flex-col items-center gap-2 p-4 rounded-xl border transition-card card-shadow-hover ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200/80 hover:border-primary-300'}`}
    >
      <img src={store.logo} alt={store.name} className="w-12 h-12 rounded-xl object-cover" />
      <span className={`text-xs font-semibold text-center leading-tight ${dark ? 'text-slate-200' : 'text-slate-800'}`}>{store.name}</span>
      <span className="text-xs font-bold text-cash-600">{store.cashbackRate}% {lang === 'en' ? 'cashback' : 'Cashback'}</span>
    </button>
  );
}

export default function HomePage() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang);
  const [searchQuery, setSearchQuery] = useState('');
  const [email, setEmail] = useState('');

  const featuredOffers = offers.filter(o => o.isExclusive || o.isPopular).slice(0, 4);
  const newOffers = offers.filter(o => o.isNew).slice(0, 4);
  const topStores = stores.filter(s => s.featured);

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Hero */}
      <section className="relative overflow-hidden">
        <div className={`absolute inset-0 ${dark ? 'bg-gradient-to-br from-slate-900 via-primary-950/50 to-slate-900' : 'bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800'}`} />
        <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px)', backgroundSize: '40px 40px' }} />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 sm:py-20 lg:py-24">
          <div className="max-w-2xl">
            <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-3 py-1.5 text-white/90 text-xs font-semibold mb-5">
              <span className="w-2 h-2 bg-cash-400 rounded-full animate-pulse" />
              {tr.home.trustCashback} · {tr.home.trustFree}
            </div>
            <h1 className="font-display font-900 text-3xl sm:text-4xl lg:text-5xl text-white leading-tight mb-4">
              {tr.home.heroTitle}
            </h1>
            <p className="text-white/80 text-base sm:text-lg leading-relaxed mb-8 max-w-xl">
              {tr.home.heroSubtitle}
            </p>

            {/* Search bar */}
            <div className="flex gap-2 bg-white/10 backdrop-blur p-1.5 rounded-xl max-w-lg">
              <div className="flex-1 flex items-center gap-2 bg-white rounded-lg px-3">
                <svg className="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input
                  value={searchQuery}
                  onChange={e => setSearchQuery(e.target.value)}
                  onKeyDown={e => e.key === 'Enter' && setPage('search')}
                  placeholder={tr.home.heroSearch}
                  className="flex-1 py-2.5 text-sm text-slate-800 placeholder-slate-400 outline-none bg-transparent"
                />
              </div>
              <button
                onClick={() => setPage('search')}
                className="px-5 py-2.5 bg-cash-600 hover:bg-cash-700 text-white text-sm font-semibold rounded-lg transition-colors"
              >
                {tr.home.heroSearchBtn}
              </button>
            </div>

            {/* Trust badges */}
            <div className="flex items-center gap-6 mt-6">
              {[
                { icon: '🏪', label: tr.home.trustCount },
                { icon: '💰', label: tr.home.trustCashback },
                { icon: '✓', label: tr.home.trustFree },
              ].map(({ icon, label }) => (
                <div key={label} className="flex items-center gap-1.5 text-white/80 text-xs">
                  <span>{icon}</span>
                  <span className="font-medium">{label}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <h2 className={`font-display font-700 text-xl sm:text-2xl mb-5 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.home.categoryTitle}</h2>
        <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
          {categories.map(cat => (
            <button
              key={cat.id}
              onClick={() => setPage('stores')}
              className={`group flex flex-col items-center gap-2 p-3 sm:p-4 rounded-xl border transition-all hover:scale-105 ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200/80 hover:border-primary-200 hover:shadow-sm'}`}
            >
              <span className="text-2xl">{cat.icon}</span>
              <span className={`text-xs font-medium text-center leading-tight ${dark ? 'text-slate-300' : 'text-slate-700'}`}>
                {cat.label[lang]}
              </span>
            </button>
          ))}
        </div>
      </section>

      {/* Featured deals */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 pb-10">
        <div className="flex items-center justify-between mb-5">
          <h2 className={`font-display font-700 text-xl sm:text-2xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.home.featuredTitle}</h2>
          <button onClick={() => setPage('stores')} className="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
            {tr.common.seeAll}
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
          </button>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {featuredOffers.map(offer => (
            <OfferCard key={offer.id} offer={offer} variant="featured" />
          ))}
        </div>
      </section>

      {/* Top cashback stores */}
      <section className={`py-10 ${dark ? 'bg-slate-900/50' : 'bg-white'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="flex items-center justify-between mb-5">
            <h2 className={`font-display font-700 text-xl sm:text-2xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.home.topStoresTitle}</h2>
            <button onClick={() => setPage('stores')} className="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
              {tr.common.seeAll}
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
            </button>
          </div>
          <div className="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            {topStores.map(store => (
              <StoreTile key={store.id} store={store} onClick={() => setPage('store-detail')} />
            ))}
          </div>
        </div>
      </section>

      {/* New offers */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <div className="flex items-center justify-between mb-5">
          <h2 className={`font-display font-700 text-xl sm:text-2xl ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.home.newOffersTitle}</h2>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {newOffers.map(offer => (
            <OfferCard key={offer.id} offer={offer} variant="compact" />
          ))}
        </div>
      </section>

      {/* Newsletter */}
      <section className={`py-12 ${dark ? 'bg-gradient-to-r from-primary-900/40 to-primary-800/20' : 'bg-gradient-to-r from-primary-700 to-primary-800'}`}>
        <div className="max-w-2xl mx-auto px-4 sm:px-6 text-center">
          <h2 className="font-display font-800 text-2xl sm:text-3xl text-white mb-2">{tr.home.newsletterTitle}</h2>
          <p className="text-white/80 text-sm sm:text-base mb-6">{tr.home.newsletterSubtitle}</p>
          <div className="flex gap-2 max-w-sm mx-auto">
            <input
              value={email}
              onChange={e => setEmail(e.target.value)}
              placeholder={tr.home.newsletterPlaceholder}
              className={`flex-1 px-4 py-2.5 rounded-xl text-sm outline-none ${dark ? 'bg-slate-800 text-white placeholder-slate-500 border border-slate-700' : 'bg-white text-slate-800 placeholder-slate-400'}`}
            />
            <button className="px-4 py-2.5 rounded-xl bg-cash-600 hover:bg-cash-700 text-white text-sm font-semibold transition-colors">
              {tr.home.newsletterBtn}
            </button>
          </div>
        </div>
      </section>
    </div>
  );
}
