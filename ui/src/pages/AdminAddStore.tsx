import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import AdminLayout from './AdminLayout';
import OfferCard from '../components/OfferCard';
import { offers } from '../data';

export default function AdminAddStore() {
  const { dark, lang, showToast } = useApp();
  const tr = t(lang).admin;
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [contentLang, setContentLang] = useState<'en' | 'de'>('en');
  const [form, setForm] = useState({
    name: 'SportCheck',
    slugEn: 'sportcheck',
    category: 'sports',
    websiteUrl: 'https://www.sport-check.de',
    affiliateUrl: '',
    logoUrl: 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=80&h=80&fit=crop&auto=format',
    descriptionEn: "Germany's leading sports equipment and fashion retailer.",
    descriptionDe: 'Deutschlands führender Sportartikel- und Modehändler.',
    offerType: 'cashback',
    discountValue: '9',
    discountUnit: '%',
    commissionPct: '14',
    cashbackPct: '9',
    code: '',
    titleEn: '9% Cashback on all orders',
    titleDe: '9 % Cashback auf alle Bestellungen',
    validFrom: '2024-12-01',
    validUntil: '2024-12-31',
  });

  const steps = [tr.step1, tr.step2, tr.step3];

  const previewOffer = {
    id: 'preview',
    storeId: 'preview',
    storeName: form.name || 'Store Name',
    storeLogo: form.logoUrl,
    type: form.offerType as 'cashback' | 'code' | 'deal',
    title: { en: form.titleEn || 'Offer Title', de: form.titleDe || 'Angebots-Titel' },
    description: { en: form.descriptionEn, de: form.descriptionDe },
    code: form.code || undefined,
    discountValue: `${form.discountValue}${form.discountUnit === '%' ? '%' : '€'}`,
    cashbackPercent: parseFloat(form.cashbackPct) || undefined,
    expiresAt: new Date(form.validUntil || Date.now() + 30 * 86400000),
    isExclusive: false, isVerified: false, isPopular: false, isNew: true,
    clicks: 0,
    commissionPercent: parseFloat(form.commissionPct) || 0,
  };

  return (
    <AdminLayout>
      <div className="p-6 max-w-4xl">
        {/* Header */}
        <h1 className={`font-display font-800 text-2xl mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.addStore}</h1>

        {/* Step indicator */}
        <div className="flex items-center gap-3 mb-6">
          {steps.map((label, i) => {
            const s = (i + 1) as 1 | 2 | 3;
            const active = step === s;
            const done = step > s;
            return (
              <div key={i} className="flex items-center gap-2">
                <button
                  onClick={() => s < step && setStep(s)}
                  className={`flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-colors ${done ? 'bg-cash-600 text-white' : active ? 'bg-primary-700 text-white' : dark ? 'bg-slate-700 text-slate-400' : 'bg-slate-200 text-slate-400'}`}
                >
                  {done ? '✓' : s}
                </button>
                <span className={`text-sm font-medium ${active ? (dark ? 'text-white' : 'text-slate-900') : dark ? 'text-slate-500' : 'text-slate-400'}`}>
                  {label}
                </span>
                {i < steps.length - 1 && <div className={`w-8 h-px ml-1 ${done ? 'bg-cash-600' : dark ? 'bg-slate-700' : 'bg-slate-200'}`} />}
              </div>
            );
          })}
        </div>

        {/* Step 1: Store info */}
        {step === 1 && (
          <div className={`rounded-2xl border p-5 ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
            {/* EN/DE tabs */}
            <div className={`flex gap-1 p-1 rounded-lg w-fit mb-5 ${dark ? 'bg-slate-700' : 'bg-slate-100'}`}>
              {(['en', 'de'] as const).map(l => (
                <button key={l} onClick={() => setContentLang(l)} className={`px-3 py-1.5 rounded-md text-sm font-semibold transition-colors ${contentLang === l ? 'bg-white text-primary-700 shadow-sm dark:bg-slate-600 dark:text-white' : dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {l === 'en' ? '🇬🇧 EN' : '🇩🇪 DE'}
                </button>
              ))}
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="sm:col-span-2">
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {lang === 'en' ? 'Store Name' : 'Shop-Name'}
                </label>
                <input value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {lang === 'en' ? 'Category' : 'Kategorie'}
                </label>
                <select value={form.category} onChange={e => setForm(f => ({ ...f, category: e.target.value }))}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`}>
                  <option value="fashion">Fashion / Mode</option>
                  <option value="electronics">Electronics / Elektronik</option>
                  <option value="sports">Sports / Sport</option>
                  <option value="beauty">Beauty</option>
                  <option value="travel">Travel / Reisen</option>
                  <option value="home">Home / Haus</option>
                </select>
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {lang === 'en' ? 'Website URL' : 'Website-URL'}
                </label>
                <input value={form.websiteUrl} onChange={e => setForm(f => ({ ...f, websiteUrl: e.target.value }))}
                  placeholder="https://www.example.de"
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none font-mono ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
              <div className="sm:col-span-2">
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {contentLang === 'en' ? 'Description (EN)' : 'Beschreibung (DE)'}
                </label>
                <textarea
                  value={contentLang === 'en' ? form.descriptionEn : form.descriptionDe}
                  onChange={e => setForm(f => contentLang === 'en' ? { ...f, descriptionEn: e.target.value } : { ...f, descriptionDe: e.target.value })}
                  rows={2}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none resize-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`}
                />
              </div>
              <div className="sm:col-span-2">
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {lang === 'en' ? 'Logo URL' : 'Logo-URL'}
                </label>
                <div className="flex gap-2">
                  <input value={form.logoUrl} onChange={e => setForm(f => ({ ...f, logoUrl: e.target.value }))}
                    className={`flex-1 px-3 py-2 rounded-lg border text-sm outline-none font-mono ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
                  {form.logoUrl && <img src={form.logoUrl} alt="" className="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700" />}
                </div>
              </div>
            </div>

            <div className="flex justify-end mt-5">
              <button onClick={() => setStep(2)} className="px-5 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors">
                {t(lang).common.next} →
              </button>
            </div>
          </div>
        )}

        {/* Step 2: Offer details */}
        {step === 2 && (
          <div className={`rounded-2xl border p-5 ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
            <div className={`flex gap-1 p-1 rounded-lg w-fit mb-5 ${dark ? 'bg-slate-700' : 'bg-slate-100'}`}>
              {(['en', 'de'] as const).map(l => (
                <button key={l} onClick={() => setContentLang(l)} className={`px-3 py-1.5 rounded-md text-sm font-semibold transition-colors ${contentLang === l ? 'bg-white text-primary-700 shadow-sm dark:bg-slate-600 dark:text-white' : dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {l === 'en' ? '🇬🇧 EN' : '🇩🇪 DE'}
                </button>
              ))}
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Offer Type' : 'Angebotstyp'}</label>
                <select value={form.offerType} onChange={e => setForm(f => ({ ...f, offerType: e.target.value }))}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`}>
                  <option value="cashback">Cashback</option>
                  <option value="code">{lang === 'en' ? 'Coupon Code' : 'Gutscheincode'}</option>
                  <option value="deal">Deal</option>
                </select>
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Discount Value' : 'Rabatt-Wert'}</label>
                <div className="flex">
                  <input value={form.discountValue} onChange={e => setForm(f => ({ ...f, discountValue: e.target.value }))} type="number"
                    className={`flex-1 px-3 py-2 rounded-l-lg border-y border-l text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
                  <select value={form.discountUnit} onChange={e => setForm(f => ({ ...f, discountUnit: e.target.value }))}
                    className={`px-2 py-2 rounded-r-lg border text-sm outline-none ${dark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-100 border-slate-200 text-slate-700'}`}>
                    <option value="%">%</option>
                    <option value="€">€</option>
                  </select>
                </div>
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Commission %' : 'Provisions-%'}</label>
                <input value={form.commissionPct} onChange={e => setForm(f => ({ ...f, commissionPct: e.target.value }))} type="number"
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Cashback % (user gets)' : 'Cashback % (für Nutzer)'}</label>
                <input value={form.cashbackPct} onChange={e => setForm(f => ({ ...f, cashbackPct: e.target.value }))} type="number"
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
              {form.offerType === 'code' && (
                <div className="sm:col-span-2">
                  <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Coupon Code' : 'Gutscheincode'}</label>
                  <input value={form.code} onChange={e => setForm(f => ({ ...f, code: e.target.value }))}
                    placeholder="e.g. SAVE20" className={`w-full px-3 py-2 rounded-lg border text-sm outline-none font-mono uppercase ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
                </div>
              )}
              <div className="sm:col-span-2">
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
                  {contentLang === 'en' ? 'Offer Title (EN)' : 'Angebots-Titel (DE)'}
                </label>
                <input
                  value={contentLang === 'en' ? form.titleEn : form.titleDe}
                  onChange={e => setForm(f => contentLang === 'en' ? { ...f, titleEn: e.target.value } : { ...f, titleDe: e.target.value })}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`}
                />
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Valid From' : 'Gültig ab'}</label>
                <input type="date" value={form.validFrom} onChange={e => setForm(f => ({ ...f, validFrom: e.target.value }))}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
              <div>
                <label className={`block text-xs font-semibold mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{lang === 'en' ? 'Valid Until' : 'Gültig bis'}</label>
                <input type="date" value={form.validUntil} onChange={e => setForm(f => ({ ...f, validUntil: e.target.value }))}
                  className={`w-full px-3 py-2 rounded-lg border text-sm outline-none ${dark ? 'bg-slate-900 border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-800'}`} />
              </div>
            </div>

            <div className="flex justify-between mt-5">
              <button onClick={() => setStep(1)} className={`px-5 py-2.5 rounded-xl border text-sm font-semibold transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                ← {t(lang).common.back}
              </button>
              <button onClick={() => setStep(3)} className="px-5 py-2.5 rounded-xl bg-primary-700 text-white text-sm font-semibold hover:bg-primary-800 transition-colors">
                {t(lang).common.next} →
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Preview + Publish */}
        {step === 3 && (
          <div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
              {(['en', 'de'] as const).map(l => (
                <div key={l} className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
                  <div className="flex items-center gap-2 mb-3">
                    <span className="text-base">{l === 'en' ? '🇬🇧' : '🇩🇪'}</span>
                    <span className={`text-sm font-semibold ${dark ? 'text-slate-300' : 'text-slate-700'}`}>{l.toUpperCase()} Preview</span>
                  </div>
                  <OfferCard offer={{ ...previewOffer, title: { en: form.titleEn, de: form.titleDe } }} variant="default" />
                </div>
              ))}
            </div>

            {/* Summary */}
            <div className={`p-4 rounded-xl border mb-5 ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-slate-50 border-slate-200'}`}>
              <h3 className={`text-sm font-semibold mb-3 ${dark ? 'text-white' : 'text-slate-900'}`}>{lang === 'en' ? 'Configuration Summary' : 'Konfigurations-Zusammenfassung'}</h3>
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                {[
                  { label: lang === 'en' ? 'Store' : 'Shop', value: form.name },
                  { label: lang === 'en' ? 'Category' : 'Kategorie', value: form.category },
                  { label: lang === 'en' ? 'Commission' : 'Provision', value: `${form.commissionPct}%` },
                  { label: lang === 'en' ? 'Cashback' : 'Cashback', value: `${form.cashbackPct}%` },
                  { label: lang === 'en' ? 'Valid from' : 'Gültig ab', value: form.validFrom },
                  { label: lang === 'en' ? 'Valid until' : 'Gültig bis', value: form.validUntil },
                  { label: lang === 'en' ? 'Type' : 'Typ', value: form.offerType },
                  { label: lang === 'en' ? 'Discount' : 'Rabatt', value: `${form.discountValue}${form.discountUnit}` },
                ].map(({ label, value }) => (
                  <div key={label}>
                    <p className={dark ? 'text-slate-500' : 'text-slate-400'}>{label}</p>
                    <p className={`font-semibold mt-0.5 ${dark ? 'text-slate-200' : 'text-slate-700'}`}>{value}</p>
                  </div>
                ))}
              </div>
            </div>

            <div className="flex justify-between">
              <button onClick={() => setStep(2)} className={`px-5 py-2.5 rounded-xl border text-sm font-semibold transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                ← {t(lang).common.back}
              </button>
              <div className="flex gap-2">
                <button onClick={() => showToast('Draft saved!', 'info')} className={`px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}>
                  {tr.saveDraft}
                </button>
                <button onClick={() => showToast('Store published successfully!', 'success')} className="px-5 py-2.5 rounded-xl bg-cash-600 text-white text-sm font-semibold hover:bg-cash-700 transition-colors">
                  {tr.publish}
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </AdminLayout>
  );
}
