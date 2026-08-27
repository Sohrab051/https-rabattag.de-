import { useApp } from '../context';
import { t } from '../i18n';

export default function CookieBanner() {
  const { dark, lang, showCookie, setShowCookie, showToast } = useApp();
  const tr = t(lang).cookie;

  if (!showCookie) return null;

  return (
    <div className={`fixed bottom-0 left-0 right-0 z-50 p-4 border-t shadow-2xl ${dark ? 'bg-slate-900 border-slate-700/60' : 'bg-white border-slate-200'}`}>
      <div className="max-w-5xl mx-auto">
        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
          <div className="flex-1 min-w-0">
            <p className={`text-sm font-semibold mb-1 font-display ${dark ? 'text-white' : 'text-slate-900'}`}>
              🍪 {tr.title}
            </p>
            <p className={`text-xs leading-relaxed ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.body}</p>
          </div>
          <div className="flex items-center gap-2 flex-shrink-0 flex-wrap">
            <button
              onClick={() => setShowCookie(false)}
              className={`px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}
            >
              {tr.rejectAll}
            </button>
            <button
              onClick={() => setShowCookie(false)}
              className={`px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors ${dark ? 'border-slate-600 text-slate-300 hover:bg-slate-800' : 'border-slate-200 text-slate-600 hover:bg-slate-50'}`}
            >
              {tr.customize}
            </button>
            <button
              onClick={() => { setShowCookie(false); showToast('Preferences saved', 'success'); }}
              className="px-4 py-1.5 text-xs font-semibold rounded-lg bg-primary-700 text-white hover:bg-primary-800 transition-colors"
            >
              {tr.acceptAll}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
