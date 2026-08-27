import { useState } from 'react';
import { useApp } from '../context';
import { offers } from '../data';
import OfferCard from '../components/OfferCard';

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  const { dark } = useApp();
  return (
    <div className="mb-10">
      <h2 className={`font-display font-700 text-lg mb-4 pb-2 border-b ${dark ? 'text-white border-slate-700' : 'text-slate-900 border-slate-200'}`}>{title}</h2>
      {children}
    </div>
  );
}

export default function DesignSystem() {
  const { dark } = useApp();
  const [toast, setToast] = useState(false);

  const colors = [
    { name: 'Primary', swatches: ['bg-primary-50', 'bg-primary-100', 'bg-primary-300', 'bg-primary-500', 'bg-primary-600', 'bg-primary-700', 'bg-primary-800', 'bg-primary-900'], labels: ['50', '100', '300', '500', '600', '700', '800', '900'] },
    { name: 'Cashback Green', swatches: ['bg-cash-50', 'bg-cash-100', 'bg-cash-400', 'bg-cash-500', 'bg-cash-600', 'bg-cash-700', 'bg-cash-800'], labels: ['50', '100', '400', '500', '600', '700', '800'] },
    { name: 'Urgency Orange', swatches: ['bg-urgent-50', 'bg-urgent-100', 'bg-urgent-400', 'bg-urgent-500', 'bg-urgent-600', 'bg-urgent-700'], labels: ['50', '100', '400', '500', '600', '700'] },
    { name: 'Neutrals', swatches: ['bg-slate-50', 'bg-slate-100', 'bg-slate-200', 'bg-slate-400', 'bg-slate-600', 'bg-slate-800', 'bg-slate-900', 'bg-slate-950'], labels: ['50', '100', '200', '400', '600', '800', '900', '950'] },
  ];

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      <div className="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        <div className="mb-10">
          <h1 className={`font-display font-900 text-3xl mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>Design System</h1>
          <p className={`text-sm ${dark ? 'text-slate-400' : 'text-slate-500'}`}>CashbackHub component library and style guide. Manrope display · Inter body · 8px base grid</p>
        </div>

        {/* Colors */}
        <Section title="Color Palette">
          <div className="flex flex-col gap-5">
            {colors.map(({ name, swatches, labels }) => (
              <div key={name}>
                <p className={`text-xs font-semibold mb-2 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{name}</p>
                <div className="flex gap-1.5">
                  {swatches.map((bg, i) => (
                    <div key={i} className="flex flex-col items-center gap-1">
                      <div className={`w-10 h-10 rounded-lg ${bg} border ${dark ? 'border-slate-700/50' : 'border-black/5'}`} />
                      <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{labels[i]}</span>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </Section>

        {/* Typography */}
        <Section title="Typography — Manrope (display) + Inter (body)">
          <div className="flex flex-col gap-3">
            {[
              { label: 'H1 — Manrope 900 32px', cls: 'font-display font-900 text-3xl', text: 'Save More on Every Purchase' },
              { label: 'H2 — Manrope 800 24px', cls: 'font-display font-800 text-2xl', text: 'Top Cashback Stores' },
              { label: 'H3 — Manrope 700 20px', cls: 'font-display font-700 text-xl', text: 'How Cashback Works' },
              { label: 'H4 — Manrope 700 16px', cls: 'font-display font-700 text-base', text: 'Store Details & Offers' },
              { label: 'Body — Inter 400 14px', cls: 'text-sm', text: 'We use cookies to enhance your browsing experience, personalise offers, and analyse traffic.' },
              { label: 'Caption — Inter 400 12px', cls: 'text-xs', text: 'Ä Ö Ü ß — German special characters render correctly with Inter & Manrope.' },
              { label: 'Button — Inter 600 14px', cls: 'text-sm font-semibold', text: 'Get Cashback · Activate Deal · Jetzt sparen' },
            ].map(({ label, cls, text }) => (
              <div key={label} className="flex items-baseline gap-4">
                <span className={`w-52 flex-shrink-0 text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{label}</span>
                <span className={`${cls} ${dark ? 'text-white' : 'text-slate-900'}`}>{text}</span>
              </div>
            ))}
          </div>
        </Section>

        {/* Buttons */}
        <Section title="Buttons">
          <div className="flex flex-wrap gap-3 mb-4">
            <button className="px-4 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors">Primary</button>
            <button className={`px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-800' : 'border-slate-300 text-slate-700 hover:bg-slate-50'}`}>Secondary</button>
            <button className="px-4 py-2.5 rounded-xl text-sm font-semibold text-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">Ghost</button>
            <button className="px-4 py-2.5 rounded-xl bg-cash-600 text-white text-sm font-semibold hover:bg-cash-700 transition-colors">Cash Green</button>
            <button className="px-4 py-2.5 rounded-xl bg-urgent-600 text-white text-sm font-semibold hover:bg-urgent-700 transition-colors">Urgency Orange</button>
            <button disabled className="px-4 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold opacity-40 cursor-not-allowed">Disabled</button>
          </div>
          <div className="flex flex-wrap gap-3">
            <button className="px-3 py-1.5 rounded-lg bg-primary-700 text-white text-xs font-semibold">Small</button>
            <button className="px-4 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold">Medium</button>
            <button className="px-6 py-3 rounded-xl bg-primary-700 text-white text-base font-semibold">Large</button>
          </div>
        </Section>

        {/* Status badges */}
        <Section title="Status Badges">
          <div className="flex flex-wrap gap-2">
            {[
              { label: 'Published', cls: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400' },
              { label: 'Pending', cls: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' },
              { label: 'Approved', cls: 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300' },
              { label: 'Draft', cls: 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' },
              { label: 'Expired', cls: 'bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500' },
              { label: 'Rejected', cls: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400' },
              { label: '🚩 Flagged', cls: 'bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' },
              { label: '✓ Verified', cls: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400' },
              { label: '★ Exclusive', cls: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' },
              { label: '🆕 New', cls: 'bg-cash-50 text-cash-700 dark:bg-cash-800/30 dark:text-cash-400' },
            ].map(({ label, cls }) => (
              <span key={label} className={`px-2 py-1 rounded-full text-xs font-semibold ${cls}`}>{label}</span>
            ))}
          </div>
        </Section>

        {/* Skeleton loading */}
        <Section title="Skeleton Loading State">
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {[1, 2, 3].map(i => (
              <div key={i} className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                <div className="flex items-start gap-3 mb-3">
                  <div className="skeleton w-10 h-10 rounded-lg" />
                  <div className="flex-1">
                    <div className="skeleton h-3 w-3/4 mb-2" />
                    <div className="skeleton h-4 w-full mb-1" />
                    <div className="skeleton h-3 w-2/3" />
                  </div>
                </div>
                <div className="skeleton h-8 w-full rounded-lg" />
              </div>
            ))}
          </div>
        </Section>

        {/* Offer cards */}
        <Section title="Offer Cards">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {offers.slice(0, 4).map(offer => (
              <OfferCard key={offer.id} offer={offer} />
            ))}
          </div>
        </Section>

        {/* Toast */}
        <Section title="Toast / Notification">
          <div className="flex flex-wrap gap-3 mb-4">
            {[
              { label: 'Success Toast', cls: 'bg-cash-600', msg: '✓ Code copied to clipboard!' },
              { label: 'Info Toast', cls: 'bg-primary-700', msg: 'ℹ Redirecting to store…' },
              { label: 'Error Toast', cls: 'bg-red-600', msg: '✗ An error occurred. Please try again.' },
            ].map(({ label, cls, msg }) => (
              <button key={label} onClick={() => setToast(true)} className={`px-3 py-2 rounded-lg text-sm font-semibold text-white ${cls}`}>{label}</button>
            ))}
          </div>
          <div className={`inline-flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white bg-slate-900`}>
            <svg className="w-4 h-4 text-cash-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" /></svg>
            Code copied to clipboard!
          </div>
        </Section>

        {/* Empty state */}
        <Section title="Empty State">
          <div className={`flex flex-col items-center justify-center py-12 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
            <div className="text-5xl mb-4">🔍</div>
            <h3 className={`font-display font-700 text-lg mb-1 ${dark ? 'text-white' : 'text-slate-900'}`}>No results found</h3>
            <p className={`text-sm mb-4 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>Try different keywords or browse all stores</p>
            <button className="px-4 py-2 rounded-xl bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors">Browse All Stores</button>
          </div>
        </Section>

        {/* Input fields */}
        <Section title="Form Inputs">
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg">
            <div>
              <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>Default</label>
              <input placeholder="Enter email address" className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none ${dark ? 'bg-slate-800 border-slate-700 text-white placeholder-slate-500' : 'bg-white border-slate-200 text-slate-800 placeholder-slate-400'}`} />
            </div>
            <div>
              <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>Focused</label>
              <input defaultValue="jan@example.de" className={`w-full px-3 py-2.5 rounded-xl border-2 text-sm outline-none border-primary-500 ${dark ? 'bg-slate-800 text-white' : 'bg-white text-slate-800'}`} />
            </div>
            <div>
              <label className={`block text-xs font-medium mb-1 text-red-500`}>Error</label>
              <input defaultValue="invalid-email" className={`w-full px-3 py-2.5 rounded-xl border-2 border-red-400 text-sm outline-none ${dark ? 'bg-slate-800 text-white' : 'bg-white text-slate-800'}`} />
              <p className="text-xs text-red-500 mt-1">Please enter a valid email address.</p>
            </div>
            <div>
              <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>Dropdown</label>
              <select className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none ${dark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-800'}`}>
                <option>🇬🇧 English (EN)</option>
                <option>🇩🇪 Deutsch (DE)</option>
              </select>
            </div>
          </div>
        </Section>

        {/* Language switcher */}
        <Section title="Language Switcher">
          <div className="flex gap-3 flex-wrap items-center">
            <div className={`flex gap-1 p-1 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'}`}>
              <button className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-700 text-white text-sm font-semibold">🇬🇧 EN</button>
              <button className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium ${dark ? 'text-slate-300 hover:bg-slate-700' : 'text-slate-600 hover:bg-slate-100'}`}>🇩🇪 DE</button>
            </div>
            <div className={`flex items-center gap-2 px-3 py-2 rounded-xl border text-sm ${dark ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-white border-slate-200 text-slate-600'}`}>
              <span>🇩🇪</span>
              <span className="font-semibold">DE</span>
              <span className={dark ? 'text-slate-600' : 'text-slate-300'}>·</span>
              <span>EUR</span>
            </div>
          </div>
        </Section>
      </div>
    </div>
  );
}
