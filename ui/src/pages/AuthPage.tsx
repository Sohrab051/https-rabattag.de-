import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';

export default function AuthPage() {
  const { dark, lang, page, setPage, showToast } = useApp();
  const tr = t(lang).auth;
  const isSignUp = page === 'auth-signup';
  const [form, setForm] = useState({ email: '', password: '', firstName: '', lastName: '', confirmPassword: '' });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    showToast(isSignUp ? 'Account created! Welcome 🎉' : 'Signed in successfully!', 'success');
    setPage('dashboard');
  };

  return (
    <div className={`min-h-screen flex ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Left decorative panel — hidden on mobile */}
      <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 flex-col items-center justify-center p-12 overflow-hidden">
        <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'radial-gradient(circle at 30% 40%, white 1px, transparent 1px)', backgroundSize: '32px 32px' }} />
        <div className="relative z-10 text-center">
          <div className="flex items-center justify-center gap-2 mb-8">
            <span className="flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl text-white font-bold text-lg">CB</span>
            <span className="font-display font-800 text-white text-2xl">CashbackHub</span>
          </div>
          <h2 className="font-display font-800 text-3xl text-white mb-4 leading-tight">
            {lang === 'en' ? 'Earn cashback on\nevery purchase' : 'Cashback bei\njedem Einkauf'}
          </h2>
          <p className="text-white/70 text-base mb-8">
            {lang === 'en' ? 'Join 280,000+ smart shoppers saving real money every day.' : 'Schließe dich 280.000+ Nutzern an, die täglich echtes Geld sparen.'}
          </p>
          {/* Stats */}
          <div className="grid grid-cols-3 gap-4">
            {[
              { value: '€2.4M', label: lang === 'en' ? 'Paid out' : 'Ausgezahlt' },
              { value: '1,200+', label: lang === 'en' ? 'Stores' : 'Shops' },
              { value: '25%', label: lang === 'en' ? 'Max cashback' : 'Max. Cashback' },
            ].map(({ value, label }) => (
              <div key={label} className="bg-white/10 rounded-xl p-3">
                <p className="font-display font-800 text-xl text-white">{value}</p>
                <p className="text-white/60 text-xs">{label}</p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Right: form */}
      <div className="flex-1 flex flex-col items-center justify-center p-6 sm:p-10">
        {/* Mobile logo */}
        <button onClick={() => setPage('home')} className="lg:hidden flex items-center gap-2 mb-8">
          <span className="flex items-center justify-center w-8 h-8 bg-primary-700 rounded-lg text-white text-sm font-bold">CB</span>
          <span className={`font-display font-800 text-lg ${dark ? 'text-white' : 'text-slate-900'}`}>CashbackHub</span>
        </button>

        <div className="w-full max-w-sm">
          <h1 className={`font-display font-800 text-2xl mb-1 ${dark ? 'text-white' : 'text-slate-900'}`}>
            {isSignUp ? tr.createAccount : tr.welcomeBack}
          </h1>
          <p className={`text-sm mb-6 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>
            {isSignUp ? tr.haveAccount : tr.noAccount}{' '}
            <button onClick={() => setPage(isSignUp ? 'auth-signin' : 'auth-signup')} className="text-primary-600 font-semibold hover:underline">
              {isSignUp ? tr.signin : tr.signup}
            </button>
          </p>

          {/* Social logins */}
          <div className="flex gap-3 mb-5">
            {['Google', 'Apple'].map(provider => (
              <button key={provider} className={`flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl border text-sm font-medium transition-colors ${dark ? 'border-slate-700 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-700 hover:bg-slate-50'}`}>
                <span className="text-base">{provider === 'Google' ? '🇬' : '🍎'}</span>
                {tr.continueWith} {provider}
              </button>
            ))}
          </div>

          <div className="flex items-center gap-3 mb-5">
            <div className={`flex-1 h-px ${dark ? 'bg-slate-700' : 'bg-slate-200'}`} />
            <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{tr.or}</span>
            <div className={`flex-1 h-px ${dark ? 'bg-slate-700' : 'bg-slate-200'}`} />
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit} className="flex flex-col gap-3">
            {isSignUp && (
              <div className="grid grid-cols-2 gap-3">
                {(['firstName', 'lastName'] as const).map(field => (
                  <div key={field}>
                    <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-300' : 'text-slate-600'}`}>{tr[field]}</label>
                    <input
                      value={form[field]}
                      onChange={e => setForm(f => ({ ...f, [field]: e.target.value }))}
                      className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none transition-colors ${dark ? 'bg-slate-800 border-slate-700 text-white focus:border-primary-500' : 'bg-white border-slate-200 text-slate-800 focus:border-primary-400'}`}
                    />
                  </div>
                ))}
              </div>
            )}

            {(['email', 'password', ...(isSignUp ? ['confirmPassword'] : [])] as const).map(field => (
              <div key={field}>
                <label className={`block text-xs font-medium mb-1 ${dark ? 'text-slate-300' : 'text-slate-600'}`}>{tr[field as keyof typeof tr]}</label>
                <input
                  type={field.includes('assword') ? 'password' : 'email'}
                  value={form[field as keyof typeof form]}
                  onChange={e => setForm(f => ({ ...f, [field]: e.target.value }))}
                  required
                  className={`w-full px-3 py-2.5 rounded-xl border text-sm outline-none transition-colors ${dark ? 'bg-slate-800 border-slate-700 text-white focus:border-primary-500' : 'bg-white border-slate-200 text-slate-800 focus:border-primary-400'}`}
                />
              </div>
            ))}

            {!isSignUp && (
              <div className="flex justify-end">
                <button type="button" className="text-xs text-primary-600 hover:underline">{tr.forgotPassword}</button>
              </div>
            )}

            <button type="submit" className="w-full py-3 rounded-xl bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold transition-colors mt-1">
              {isSignUp ? tr.signup : tr.signin}
            </button>

            {isSignUp && (
              <p className={`text-xs text-center leading-relaxed ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{tr.terms}</p>
            )}
          </form>
        </div>
      </div>
    </div>
  );
}
