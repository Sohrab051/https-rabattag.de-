import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';

export default function WithdrawalPage() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang).withdrawal;
  const [method, setMethod] = useState<'paypal' | 'sepa'>('paypal');
  const [amount, setAmount] = useState('41.09');
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [form, setForm] = useState({ email: 'jan.mueller@gmail.com', iban: 'DE89 3704 0044 0532 0130 00', bic: 'COBADEFFXXX', holder: 'Jan Müller' });

  const available = 41.09;

  if (step === 3) {
    return (
      <div className={`min-h-screen flex items-center justify-center ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
        <div className="text-center max-w-sm px-4">
          <div className="w-16 h-16 rounded-full bg-cash-100 dark:bg-cash-800/30 flex items-center justify-center mx-auto mb-4">
            <svg className="w-8 h-8 text-cash-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" /></svg>
          </div>
          <h1 className={`font-display font-800 text-2xl mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.success}</h1>
          <p className={`text-sm mb-2 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.successMsg}</p>
          <p className={`text-3xl font-display font-900 text-cash-600 my-4`}>€{parseFloat(amount).toFixed(2)}</p>
          <p className={`text-sm mb-6 ${dark ? 'text-slate-500' : 'text-slate-400'}`}>
            {lang === 'en' ? 'to' : 'an'} {method === 'paypal' ? form.email : form.iban}
          </p>
          <button onClick={() => setPage('dashboard')} className="w-full py-3 rounded-xl bg-primary-700 text-white font-semibold hover:bg-primary-800 transition-colors">
            {lang === 'en' ? 'Back to Dashboard' : 'Zurück zum Dashboard'}
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      <div className="max-w-lg mx-auto px-4 sm:px-6 py-8">
        {/* Back */}
        <button onClick={() => step === 1 ? setPage('dashboard') : setStep(1)} className={`flex items-center gap-1.5 text-sm font-medium mb-6 ${dark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-800'}`}>
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
          {t(lang).common.back}
        </button>

        <h1 className={`font-display font-800 text-2xl mb-1 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.title}</h1>

        {/* Progress */}
        <div className="flex items-center gap-1 mb-6">
          {[1, 2].map(s => (
            <div key={s} className={`flex-1 h-1.5 rounded-full transition-colors ${s <= step ? 'bg-primary-700' : dark ? 'bg-slate-700' : 'bg-slate-200'}`} />
          ))}
        </div>

        {step === 1 && (
          <div className="flex flex-col gap-4">
            {/* Balance display */}
            <div className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}>
              <p className={`text-xs mb-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.available}</p>
              <p className={`font-display font-800 text-2xl text-cash-600`}>€{available.toFixed(2)}</p>
            </div>

            {/* Method selection */}
            <div>
              <p className={`text-sm font-semibold mb-2 ${dark ? 'text-slate-200' : 'text-slate-700'}`}>{tr.selectMethod}</p>
              <div className="flex gap-2">
                {(['paypal', 'sepa'] as const).map(m => (
                  <button
                    key={m}
                    onClick={() => setMethod(m)}
                    className={`flex-1 flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-colors ${method === m ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20' : dark ? 'border-slate-700 hover:border-slate-600' : 'border-slate-200 hover:border-slate-300'}`}
                  >
                    <span className="text-2xl">{m === 'paypal' ? '💳' : '🏦'}</span>
                    <span className={`text-sm font-semibold ${method === m ? 'text-primary-700 dark:text-primary-300' : dark ? 'text-slate-300' : 'text-slate-700'}`}>
                      {m === 'paypal' ? tr.paypal : tr.bankTransfer}
                    </span>
                  </button>
                ))}
              </div>
            </div>

            {/* Amount */}
            <div>
              <label className={`block text-sm font-semibold mb-2 ${dark ? 'text-slate-200' : 'text-slate-700'}`}>{tr.amount}</label>
              <div className={`flex items-center gap-2 px-4 py-3 rounded-xl border-2 ${dark ? 'bg-slate-800 border-primary-600/50' : 'bg-white border-primary-300'}`}>
                <span className={`text-lg font-bold ${dark ? 'text-slate-400' : 'text-slate-400'}`}>€</span>
                <input
                  value={amount}
                  onChange={e => setAmount(e.target.value)}
                  type="number"
                  min="10"
                  max={available}
                  step="0.01"
                  className={`flex-1 text-lg font-display font-700 outline-none bg-transparent ${dark ? 'text-white' : 'text-slate-900'}`}
                />
              </div>
              <div className="flex justify-between text-xs mt-1">
                <span className={dark ? 'text-slate-500' : 'text-slate-400'}>{tr.minAmount}</span>
                <button onClick={() => setAmount(available.toFixed(2))} className="text-primary-600 font-semibold hover:underline">Max</button>
              </div>
            </div>

            <button
              onClick={() => setStep(2)}
              disabled={parseFloat(amount) < 10 || parseFloat(amount) > available}
              className="w-full py-3 rounded-xl bg-primary-700 text-white font-semibold hover:bg-primary-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
              {t(lang).common.next}
            </button>
          </div>
        )}

        {step === 2 && (
          <div className="flex flex-col gap-4">
            {method === 'paypal' ? (
              <div>
                <label className={`block text-sm font-semibold mb-2 ${dark ? 'text-slate-200' : 'text-slate-700'}`}>PayPal Email</label>
                <input value={form.email} onChange={e => setForm(f => ({ ...f, email: e.target.value }))} className={`w-full px-4 py-3 rounded-xl border text-sm outline-none ${dark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-800'}`} />
              </div>
            ) : (
              <>
                {[
                  { key: 'holder', label: tr.accountHolder },
                  { key: 'iban', label: tr.iban },
                  { key: 'bic', label: tr.bic },
                ].map(({ key, label }) => (
                  <div key={key}>
                    <label className={`block text-sm font-semibold mb-2 ${dark ? 'text-slate-200' : 'text-slate-700'}`}>{label}</label>
                    <input value={form[key as keyof typeof form]} onChange={e => setForm(f => ({ ...f, [key]: e.target.value }))} className={`w-full px-4 py-3 rounded-xl border text-sm outline-none font-mono ${dark ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-200 text-slate-800'}`} />
                  </div>
                ))}
              </>
            )}

            {/* Summary */}
            <div className={`p-4 rounded-xl border ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-slate-50 border-slate-200'}`}>
              <div className="flex justify-between text-sm mb-2">
                <span className={dark ? 'text-slate-400' : 'text-slate-500'}>{lang === 'en' ? 'Amount' : 'Betrag'}</span>
                <span className={`font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>€{parseFloat(amount).toFixed(2)}</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className={dark ? 'text-slate-400' : 'text-slate-500'}>{lang === 'en' ? 'Method' : 'Methode'}</span>
                <span className={`font-semibold ${dark ? 'text-white' : 'text-slate-900'}`}>{method === 'paypal' ? 'PayPal' : 'SEPA'}</span>
              </div>
            </div>

            <button onClick={() => setStep(3)} className="w-full py-3 rounded-xl bg-primary-700 text-white font-semibold hover:bg-primary-800 transition-colors">
              {tr.confirm}
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
