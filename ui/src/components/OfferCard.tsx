import { useState, useEffect } from 'react';
import type { Offer } from '../data';
import { useApp } from '../context';
import { t } from '../i18n';

function Countdown({ expiresAt }: { expiresAt: Date }) {
  const [remaining, setRemaining] = useState('');
  const { lang } = useApp();
  const tr = t(lang).offer;

  useEffect(() => {
    const calc = () => {
      const diff = expiresAt.getTime() - Date.now();
      if (diff <= 0) { setRemaining('Expired'); return; }
      const d = Math.floor(diff / 86400000);
      const h = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      if (d > 0) setRemaining(`${d}${tr.days} ${h}${tr.hours}`);
      else if (h > 0) setRemaining(`${h}${tr.hours} ${m}${tr.minutes}`);
      else setRemaining(`${m}${tr.minutes}`);
    };
    calc();
    const id = setInterval(calc, 60000);
    return () => clearInterval(id);
  }, [expiresAt, tr]);

  const isUrgent = expiresAt.getTime() - Date.now() < 3 * 86400000;
  return (
    <span className={`inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full ${isUrgent ? 'bg-urgent-50 text-urgent-700 dark:bg-urgent-700/20 dark:text-urgent-400 pulse-urgent' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'}`}>
      <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      {remaining}
    </span>
  );
}

interface Props {
  offer: Offer;
  variant?: 'default' | 'featured' | 'compact';
}

export default function OfferCard({ offer, variant = 'default' }: Props) {
  const { dark, lang, showToast } = useApp();
  const tr = t(lang);
  const [codeRevealed, setCodeRevealed] = useState(false);
  const [copied, setCopied] = useState(false);
  const [showModal, setShowModal] = useState(false);

  const handleReveal = () => {
    if (offer.type === 'code') {
      setCodeRevealed(true);
    } else {
      setShowModal(true);
    }
  };

  const handleCopy = () => {
    if (offer.code) {
      navigator.clipboard.writeText(offer.code).catch(() => {});
      setCopied(true);
      showToast(tr.offer.copied, 'success');
      setTimeout(() => setCopied(false), 2000);
    }
  };

  const typeColor = offer.type === 'cashback'
    ? 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400'
    : offer.type === 'code'
    ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300'
    : 'bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300';

  const isCompact = variant === 'compact';

  return (
    <>
      <div className={`rounded-xl border transition-card card-shadow card-shadow-hover overflow-hidden ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200/80'} ${isCompact ? '' : ''}`}>
        {/* Header */}
        <div className={`flex items-start gap-3 ${isCompact ? 'p-3' : 'p-4'}`}>
          <div className="flex-shrink-0">
            <img
              src={offer.storeLogo}
              alt={offer.storeName}
              className="w-10 h-10 rounded-lg object-cover bg-slate-100 dark:bg-slate-700"
            />
          </div>
          <div className="flex-1 min-w-0">
            <div className="flex items-center gap-1.5 flex-wrap mb-0.5">
              <span className={`text-xs font-semibold px-1.5 py-0.5 rounded-md ${typeColor}`}>
                {offer.type === 'cashback' ? tr.offer.cashback : offer.type === 'code' ? tr.offer.code : tr.offer.deal}
              </span>
              {offer.isExclusive && <span className="text-xs font-semibold px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">{tr.offer.exclusive}</span>}
              {offer.isNew && <span className="text-xs font-semibold px-1.5 py-0.5 rounded-md bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400">{tr.offer.new}</span>}
            </div>
            <p className={`font-display font-700 text-sm leading-tight ${dark ? 'text-white' : 'text-slate-900'}`}>
              {offer.title[lang]}
            </p>
            {!isCompact && (
              <p className={`text-xs mt-0.5 line-clamp-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                {offer.description[lang]}
              </p>
            )}
          </div>

          {/* Cashback badge */}
          {offer.cashbackPercent && (
            <div className="flex-shrink-0 text-right">
              <div className="inline-flex flex-col items-center justify-center bg-cash-600 text-white rounded-lg px-2 py-1">
                <span className="text-xs font-semibold leading-none">{offer.cashbackPercent}%</span>
                <span className="text-[9px] uppercase tracking-wide leading-none mt-0.5">back</span>
              </div>
            </div>
          )}
        </div>

        {/* Discount value */}
        {!isCompact && (
          <div className={`mx-4 py-2 border-t border-b flex items-center justify-between ${dark ? 'border-slate-700/60' : 'border-slate-100'}`}>
            <span className={`text-xl font-display font-800 ${dark ? 'text-white' : 'text-slate-900'}`}>
              {offer.discountValue}
            </span>
            <Countdown expiresAt={offer.expiresAt} />
          </div>
        )}

        {/* Code reveal / CTA */}
        <div className={`${isCompact ? 'px-3 pb-3' : 'p-4'} pt-3`}>
          {codeRevealed && offer.code ? (
            <div className="flex gap-2">
              <div className={`flex-1 flex items-center justify-center py-2 px-3 rounded-lg border-2 border-dashed font-mono text-sm font-bold tracking-widest reveal-code ${dark ? 'border-primary-600 bg-primary-900/20 text-primary-300' : 'border-primary-300 bg-primary-50 text-primary-700'}`}>
                {offer.code}
              </div>
              <button
                onClick={handleCopy}
                className={`px-3 py-2 rounded-lg text-sm font-semibold transition-colors ${copied ? 'bg-cash-600 text-white' : 'bg-primary-700 text-white hover:bg-primary-800'}`}
              >
                {copied ? (
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" /></svg>
                ) : (
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                )}
              </button>
            </div>
          ) : (
            <button
              onClick={handleReveal}
              className="w-full py-2 rounded-lg text-sm font-semibold bg-primary-700 text-white hover:bg-primary-800 transition-colors"
            >
              {offer.type === 'code' ? tr.offer.getCode : tr.offer.activateDeal}
            </button>
          )}

          {!isCompact && (
            <div className="flex items-center justify-between mt-2">
              {offer.isVerified && (
                <span className={`flex items-center gap-1 text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  <svg className="w-3 h-3 text-cash-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                  {tr.offer.verified}
                </span>
              )}
              <span className={`text-xs ml-auto ${dark ? 'text-slate-500' : 'text-slate-400'}`}>
                {offer.clicks.toLocaleString()} uses
              </span>
            </div>
          )}
        </div>
      </div>

      {/* Activate Deal Modal */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onClick={() => setShowModal(false)}>
          <div
            className={`w-full max-w-md rounded-2xl p-6 shadow-2xl ${dark ? 'bg-slate-800' : 'bg-white'}`}
            onClick={e => e.stopPropagation()}
          >
            <div className="flex items-center gap-3 mb-4">
              <img src={offer.storeLogo} alt={offer.storeName} className="w-12 h-12 rounded-xl object-cover" />
              <div>
                <h3 className={`font-display font-700 text-base ${dark ? 'text-white' : 'text-slate-900'}`}>{offer.storeName}</h3>
                <p className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{offer.title[lang]}</p>
              </div>
            </div>

            {offer.cashbackPercent && (
              <div className="flex items-center justify-center gap-3 py-4 mb-4 rounded-xl bg-cash-50 dark:bg-cash-800/20">
                <span className="text-3xl font-display font-900 text-cash-600">{offer.cashbackPercent}%</span>
                <div>
                  <p className="text-sm font-semibold text-cash-700 dark:text-cash-400">{tr.offer.cashback}</p>
                  <p className="text-xs text-cash-600/80 dark:text-cash-500">tracked automatically</p>
                </div>
              </div>
            )}

            <div className={`text-xs leading-relaxed mb-4 p-3 rounded-lg ${dark ? 'bg-slate-700/50 text-slate-400' : 'bg-slate-50 text-slate-500'}`}>
              {tr.offer.terms}
            </div>

            <div className="flex gap-3">
              <button onClick={() => setShowModal(false)} className={`flex-1 py-2.5 rounded-xl text-sm font-semibold border transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                {tr.common.cancel}
              </button>
              <button
                onClick={() => { setShowModal(false); showToast('Redirecting to store…', 'info'); }}
                className="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-primary-700 text-white hover:bg-primary-800 transition-colors"
              >
                {tr.offer.goToStore}
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
