import { useState } from 'react';
import { useApp } from '../context';
import { t } from '../i18n';
import { blogPosts } from '../data';

export default function BlogPage() {
  const { dark, lang, setPage } = useApp();
  const tr = t(lang).blog;
  const [activePost, setActivePost] = useState<string | null>(null);

  const featured = blogPosts.filter(p => p.featured);
  const rest = blogPosts.filter(p => !p.featured);
  const post = activePost ? blogPosts.find(p => p.id === activePost) : null;

  if (post) {
    return (
      <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
        <div className="max-w-3xl mx-auto px-4 sm:px-6 py-8">
          <button
            onClick={() => setActivePost(null)}
            className={`flex items-center gap-1.5 text-sm font-medium mb-6 ${dark ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-800'}`}
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" /></svg>
            {lang === 'en' ? 'Back to Blog' : 'Zurück zum Blog'}
          </button>

          <div className="mb-3 flex items-center gap-2">
            <span className={`text-xs px-2 py-1 rounded-full font-semibold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300`}>
              {post.category}
            </span>
            <span className={`text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.minRead(post.readTime)}</span>
          </div>

          <h1 className={`font-display font-900 text-2xl sm:text-3xl mb-3 leading-tight ${dark ? 'text-white' : 'text-slate-900'}`}>
            {post.title[lang]}
          </h1>

          <div className="flex items-center gap-3 mb-6">
            <div className={`w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold ${dark ? 'bg-primary-600' : 'bg-primary-700'}`}>
              {post.author.split(' ').map(n => n[0]).join('')}
            </div>
            <div>
              <p className={`text-sm font-semibold ${dark ? 'text-slate-200' : 'text-slate-800'}`}>{post.author}</p>
              <p className={`text-xs ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{post.date.toLocaleDateString()}</p>
            </div>
          </div>

          <img
            src={post.image}
            alt={post.title[lang]}
            className="w-full h-56 sm:h-72 object-cover rounded-2xl mb-6"
          />

          <div className={`prose max-w-none text-sm leading-relaxed ${dark ? 'text-slate-300' : 'text-slate-700'}`}>
            <p className="text-base mb-4 font-medium">{post.excerpt[lang]}</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <p className="mt-4">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          </div>

          {/* Related */}
          <div className="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700/60">
            <h3 className={`font-display font-700 text-lg mb-4 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.relatedArticles}</h3>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              {blogPosts.filter(p => p.id !== post.id).slice(0, 2).map(p => (
                <button key={p.id} onClick={() => setActivePost(p.id)} className={`flex gap-3 p-3 rounded-xl border text-left transition-colors ${dark ? 'bg-slate-800 border-slate-700/60 hover:border-primary-600/50' : 'bg-white border-slate-200 hover:border-primary-200'}`}>
                  <img src={p.image} alt={p.title[lang]} className="w-16 h-16 rounded-lg object-cover flex-shrink-0" />
                  <div>
                    <p className={`text-sm font-semibold leading-tight ${dark ? 'text-white' : 'text-slate-900'}`}>{p.title[lang]}</p>
                    <p className={`text-xs mt-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.minRead(p.readTime)}</p>
                  </div>
                </button>
              ))}
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className={`min-h-screen ${dark ? 'bg-slate-950' : 'bg-slate-50'}`}>
      {/* Header */}
      <div className={`py-10 ${dark ? 'bg-slate-900' : 'bg-white'} border-b ${dark ? 'border-slate-700/60' : 'border-slate-200'}`}>
        <div className="max-w-5xl mx-auto px-4 sm:px-6 text-center">
          <h1 className={`font-display font-900 text-3xl sm:text-4xl mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>{tr.title}</h1>
          <p className={`text-base ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{tr.subtitle}</p>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        {/* Featured */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
          {featured.map(post => (
            <button
              key={post.id}
              onClick={() => setActivePost(post.id)}
              className={`group flex flex-col rounded-2xl overflow-hidden border transition-card card-shadow-hover text-left ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}
            >
              <div className="overflow-hidden">
                <img src={post.image} alt={post.title[lang]} className="w-full h-44 object-cover transition-transform duration-300 group-hover:scale-105" />
              </div>
              <div className="p-4 flex-1">
                <div className="flex items-center gap-2 mb-2">
                  <span className={`text-xs px-2 py-0.5 rounded-full font-semibold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300`}>{post.category}</span>
                  <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{tr.minRead(post.readTime)}</span>
                </div>
                <h2 className={`font-display font-700 text-base leading-snug mb-2 ${dark ? 'text-white' : 'text-slate-900'}`}>{post.title[lang]}</h2>
                <p className={`text-xs line-clamp-2 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{post.excerpt[lang]}</p>
                <div className="flex items-center gap-2 mt-3">
                  <div className={`w-5 h-5 rounded-full flex items-center justify-center text-white text-[9px] font-bold bg-primary-600`}>
                    {post.author.split(' ').map(n => n[0]).join('')}
                  </div>
                  <span className={`text-xs font-medium ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{post.author} · {post.date.toLocaleDateString()}</span>
                </div>
              </div>
            </button>
          ))}
        </div>

        {/* List */}
        <div className="flex flex-col gap-3">
          {rest.map(post => (
            <button
              key={post.id}
              onClick={() => setActivePost(post.id)}
              className={`flex gap-4 p-4 rounded-xl border text-left transition-card card-shadow-hover ${dark ? 'bg-slate-800 border-slate-700/60' : 'bg-white border-slate-200'}`}
            >
              <img src={post.image} alt={post.title[lang]} className="w-20 h-20 rounded-xl object-cover flex-shrink-0" />
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 mb-1">
                  <span className={`text-xs px-1.5 py-0.5 rounded-md font-semibold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300`}>{post.category}</span>
                  <span className={`text-xs ${dark ? 'text-slate-500' : 'text-slate-400'}`}>{tr.minRead(post.readTime)}</span>
                </div>
                <h3 className={`font-display font-700 text-base leading-snug ${dark ? 'text-white' : 'text-slate-900'}`}>{post.title[lang]}</h3>
                <p className={`text-xs mt-1 line-clamp-1 ${dark ? 'text-slate-400' : 'text-slate-500'}`}>{post.excerpt[lang]}</p>
              </div>
              <div className="flex items-center">
                <svg className={`w-5 h-5 ${dark ? 'text-slate-500' : 'text-slate-300'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
              </div>
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
