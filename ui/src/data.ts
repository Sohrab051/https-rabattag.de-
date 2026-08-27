export interface Store {
  id: string;
  name: string;
  slug: string;
  logo: string;
  category: string;
  cashbackRate: number;
  cashbackType: 'percent' | 'fixed';
  rating: number;
  reviewCount: number;
  offerCount: number;
  featured: boolean;
  description: { en: string; de: string };
  banner?: string;
  since: string;
  color: string;
}

export interface Offer {
  id: string;
  storeId: string;
  storeName: string;
  storeLogo: string;
  type: 'cashback' | 'code' | 'deal';
  title: { en: string; de: string };
  description: { en: string; de: string };
  code?: string;
  discountValue: string;
  cashbackPercent?: number;
  expiresAt: Date;
  isExclusive: boolean;
  isVerified: boolean;
  isPopular: boolean;
  isNew: boolean;
  clicks: number;
  commissionPercent: number;
}

export interface Transaction {
  id: string;
  storeName: string;
  storeLogo: string;
  amount: number;
  cashbackAmount: number;
  date: Date;
  status: 'pending' | 'approved' | 'paid' | 'rejected';
  orderId: string;
  isFraud?: boolean;
}

export interface BlogPost {
  id: string;
  title: { en: string; de: string };
  excerpt: { en: string; de: string };
  content: { en: string; de: string };
  category: string;
  image: string;
  author: string;
  date: Date;
  readTime: number;
  featured: boolean;
}

export const categories = [
  { id: 'fashion', icon: '👗', label: { en: 'Fashion', de: 'Mode' }, color: 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300' },
  { id: 'electronics', icon: '💻', label: { en: 'Electronics', de: 'Elektronik' }, color: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' },
  { id: 'travel', icon: '✈️', label: { en: 'Travel', de: 'Reisen' }, color: 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300' },
  { id: 'beauty', icon: '💄', label: { en: 'Beauty', de: 'Beauty' }, color: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' },
  { id: 'home', icon: '🏠', label: { en: 'Home & Garden', de: 'Haus & Garten' }, color: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' },
  { id: 'sports', icon: '⚽', label: { en: 'Sports', de: 'Sport' }, color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' },
  { id: 'food', icon: '🍕', label: { en: 'Food & Drink', de: 'Essen & Trinken' }, color: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300' },
  { id: 'books', icon: '📚', label: { en: 'Books & Media', de: 'Bücher & Medien' }, color: 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300' },
  { id: 'automotive', icon: '🚗', label: { en: 'Automotive', de: 'Auto & Motorrad' }, color: 'bg-slate-100 text-slate-700 dark:bg-slate-700/50 dark:text-slate-300' },
  { id: 'health', icon: '💊', label: { en: 'Health', de: 'Gesundheit' }, color: 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300' },
  { id: 'kids', icon: '🧸', label: { en: 'Kids & Toys', de: 'Kinder & Spielzeug' }, color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' },
  { id: 'finance', icon: '💳', label: { en: 'Finance', de: 'Finanzen' }, color: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' },
];

export const stores: Store[] = [
  {
    id: '1', name: 'Zalando', slug: 'zalando',
    logo: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=80&h=80&fit=crop&auto=format',
    category: 'fashion', cashbackRate: 8, cashbackType: 'percent',
    rating: 4.6, reviewCount: 12400, offerCount: 14, featured: true,
    description: { en: 'Europe\'s largest online fashion retailer with 2,000+ brands.', de: 'Europas größter Online-Modehändler mit über 2.000 Marken.' },
    since: '2012', color: '#FF6900',
  },
  {
    id: '2', name: 'MediaMarkt', slug: 'mediamarkt',
    logo: 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=80&h=80&fit=crop&auto=format',
    category: 'electronics', cashbackRate: 4, cashbackType: 'percent',
    rating: 4.2, reviewCount: 8900, offerCount: 22, featured: true,
    description: { en: 'Germany\'s leading consumer electronics retailer.', de: 'Deutschlands führender Elektronikhändler.' },
    since: '2010', color: '#CC071E',
  },
  {
    id: '3', name: 'Booking.com', slug: 'booking',
    logo: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&h=80&fit=crop&auto=format',
    category: 'travel', cashbackRate: 6.5, cashbackType: 'percent',
    rating: 4.7, reviewCount: 24600, offerCount: 8, featured: true,
    description: { en: 'Global leader in online accommodation booking.', de: 'Weltmarktführer bei Online-Hotelreservierungen.' },
    since: '2015', color: '#003580',
  },
  {
    id: '4', name: 'Douglas', slug: 'douglas',
    logo: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=80&h=80&fit=crop&auto=format',
    category: 'beauty', cashbackRate: 12, cashbackType: 'percent',
    rating: 4.4, reviewCount: 6200, offerCount: 18, featured: true,
    description: { en: 'Europe\'s number 1 beauty destination.', de: 'Europas Nr. 1 Beauty-Destination.' },
    since: '2013', color: '#BF1F2E',
  },
  {
    id: '5', name: 'IKEA', slug: 'ikea',
    logo: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=80&h=80&fit=crop&auto=format',
    category: 'home', cashbackRate: 3, cashbackType: 'percent',
    rating: 4.3, reviewCount: 18700, offerCount: 6, featured: false,
    description: { en: 'Swedish furniture and home accessories retailer.', de: 'Schwedischer Möbel- und Einrichtungshändler.' },
    since: '2018', color: '#0058A3',
  },
  {
    id: '6', name: 'Adidas', slug: 'adidas',
    logo: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=80&h=80&fit=crop&auto=format',
    category: 'sports', cashbackRate: 10, cashbackType: 'percent',
    rating: 4.5, reviewCount: 9800, offerCount: 11, featured: true,
    description: { en: 'Global sportswear and athletic footwear brand.', de: 'Globale Sportbekleidungs- und Schuhmarke.' },
    since: '2011', color: '#000000',
  },
  {
    id: '7', name: 'HelloFresh', slug: 'hellofresh',
    logo: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=80&h=80&fit=crop&auto=format',
    category: 'food', cashbackRate: 15, cashbackType: 'fixed',
    rating: 4.1, reviewCount: 4300, offerCount: 4, featured: true,
    description: { en: 'The world\'s leading meal kit delivery service.', de: 'Der weltweit führende Kochbox-Lieferdienst.' },
    since: '2016', color: '#6ABF4B',
  },
  {
    id: '8', name: 'Thalia', slug: 'thalia',
    logo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&auto=format',
    category: 'books', cashbackRate: 7, cashbackType: 'percent',
    rating: 4.6, reviewCount: 3100, offerCount: 9, featured: false,
    description: { en: 'Germany\'s largest bookstore chain.', de: 'Deutschlands größte Buchhandelsgruppe.' },
    since: '2014', color: '#5C2D91',
  },
];

const now = new Date();
const days = (d: number) => new Date(now.getTime() + d * 86400000);

export const offers: Offer[] = [
  {
    id: 'o1', storeId: '1', storeName: 'Zalando', storeLogo: stores[0].logo,
    type: 'code', title: { en: '20% off your first order', de: '20 % auf deine erste Bestellung' },
    description: { en: 'New customers only. Use code at checkout.', de: 'Nur für Neukunden. Code an der Kasse eingeben.' },
    code: 'WELCOME20', discountValue: '20%', cashbackPercent: 8,
    expiresAt: days(3), isExclusive: true, isVerified: true, isPopular: true, isNew: false,
    clicks: 14200, commissionPercent: 12,
  },
  {
    id: 'o2', storeId: '1', storeName: 'Zalando', storeLogo: stores[0].logo,
    type: 'cashback', title: { en: '8% Cashback on all orders', de: '8 % Cashback auf alle Bestellungen' },
    description: { en: 'Activate before shopping for automatic cashback tracking.', de: 'Vor dem Einkauf aktivieren für automatisches Cashback-Tracking.' },
    discountValue: '8% cashback', cashbackPercent: 8,
    expiresAt: days(30), isExclusive: false, isVerified: true, isPopular: false, isNew: false,
    clicks: 8900, commissionPercent: 12,
  },
  {
    id: 'o3', storeId: '2', storeName: 'MediaMarkt', storeLogo: stores[1].logo,
    type: 'code', title: { en: '€50 off orders over €500', de: '50 € Rabatt ab 500 € Bestellwert' },
    description: { en: 'Valid on all electronics. Code applies at checkout.', de: 'Gültig auf alle Elektronikprodukte.' },
    code: 'TECH50', discountValue: '€50', cashbackPercent: 4,
    expiresAt: days(1), isExclusive: true, isVerified: true, isPopular: true, isNew: true,
    clicks: 6700, commissionPercent: 6,
  },
  {
    id: 'o4', storeId: '3', storeName: 'Booking.com', storeLogo: stores[2].logo,
    type: 'cashback', title: { en: '6.5% Cashback on hotels', de: '6,5 % Cashback auf Hotelbuchungen' },
    description: { en: 'Book via our link for automatic cashback on all accommodations.', de: 'Über unseren Link buchen für automatisches Cashback.' },
    discountValue: '6.5% cashback', cashbackPercent: 6.5,
    expiresAt: days(90), isExclusive: false, isVerified: true, isPopular: true, isNew: false,
    clicks: 22100, commissionPercent: 10,
  },
  {
    id: 'o5', storeId: '4', storeName: 'Douglas', storeLogo: stores[3].logo,
    type: 'deal', title: { en: 'Up to 40% off selected fragrances', de: 'Bis zu 40 % auf ausgewählte Düfte' },
    description: { en: 'Limited selection. No code needed — discount applied automatically.', de: 'Begrenzte Auswahl. Kein Code nötig — Rabatt wird automatisch abgezogen.' },
    discountValue: 'Up to 40%', cashbackPercent: 12,
    expiresAt: days(2), isExclusive: false, isVerified: true, isPopular: false, isNew: true,
    clicks: 4400, commissionPercent: 18,
  },
  {
    id: 'o6', storeId: '6', storeName: 'Adidas', storeLogo: stores[5].logo,
    type: 'code', title: { en: 'Extra 15% off sale items', de: '15 % Extra-Rabatt auf Sale-Artikel' },
    description: { en: 'Stack with existing sale prices. Selected styles only.', de: 'Zusätzlich auf bereits reduzierte Artikel.' },
    code: 'EXTRA15', discountValue: '15%', cashbackPercent: 10,
    expiresAt: days(5), isExclusive: true, isVerified: true, isPopular: true, isNew: false,
    clicks: 11300, commissionPercent: 14,
  },
  {
    id: 'o7', storeId: '7', storeName: 'HelloFresh', storeLogo: stores[6].logo,
    type: 'code', title: { en: '€75 off your first 5 boxes', de: '75 € auf deine ersten 5 Boxen' },
    description: { en: '€15 off each of your first 5 HelloFresh boxes. New subscribers.', de: 'Je 15 € auf jede deiner ersten 5 HelloFresh-Boxen.' },
    code: 'FRESH75', discountValue: '€75', cashbackPercent: 15,
    expiresAt: days(14), isExclusive: true, isVerified: true, isPopular: true, isNew: true,
    clicks: 8800, commissionPercent: 20,
  },
  {
    id: 'o8', storeId: '5', storeName: 'IKEA', storeLogo: stores[4].logo,
    type: 'cashback', title: { en: '3% Cashback on all orders', de: '3 % Cashback auf alle Bestellungen' },
    description: { en: 'Activate deal before adding items to your cart.', de: 'Deal aktivieren, bevor du Artikel in den Warenkorb legst.' },
    discountValue: '3% cashback', cashbackPercent: 3,
    expiresAt: days(60), isExclusive: false, isVerified: true, isPopular: false, isNew: false,
    clicks: 3200, commissionPercent: 5,
  },
];

export const transactions: Transaction[] = [
  { id: 't1', storeName: 'Zalando', storeLogo: stores[0].logo, amount: 124.99, cashbackAmount: 9.99, date: new Date('2024-11-28'), status: 'approved', orderId: 'ZAL-88291' },
  { id: 't2', storeName: 'Booking.com', storeLogo: stores[2].logo, amount: 340.00, cashbackAmount: 22.10, date: new Date('2024-11-21'), status: 'paid', orderId: 'BK-44712' },
  { id: 't3', storeName: 'Adidas', storeLogo: stores[5].logo, amount: 89.95, cashbackAmount: 8.99, date: new Date('2024-11-15'), status: 'pending', orderId: 'ADI-10293' },
  { id: 't4', storeName: 'Douglas', storeLogo: stores[3].logo, amount: 67.50, cashbackAmount: 8.10, date: new Date('2024-11-10'), status: 'paid', orderId: 'DOU-73842' },
  { id: 't5', storeName: 'MediaMarkt', storeLogo: stores[1].logo, amount: 499.00, cashbackAmount: 19.96, date: new Date('2024-10-30'), status: 'approved', orderId: 'MM-29481', isFraud: false },
  { id: 't6', storeName: 'HelloFresh', storeLogo: stores[6].logo, amount: 45.00, cashbackAmount: 6.75, date: new Date('2024-10-22'), status: 'pending', orderId: 'HF-88372' },
  { id: 't7', storeName: 'IKEA', storeLogo: stores[4].logo, amount: 210.00, cashbackAmount: 6.30, date: new Date('2024-10-15'), status: 'rejected', orderId: 'IKE-11929', isFraud: true },
];

export const blogPosts: BlogPost[] = [
  {
    id: 'b1',
    title: { en: 'How to Maximise Your Cashback in Q4 2024', de: 'So maximierst du dein Cashback im Q4 2024' },
    excerpt: { en: 'Black Friday, Cyber Monday, Christmas — the calendar is packed with cashback opportunities. Here\'s how to stack every possible saving.', de: 'Black Friday, Cyber Monday, Weihnachten – das Quartal steckt voller Cashback-Möglichkeiten. So stapelst du jeden möglichen Vorteil.' },
    content: { en: 'Full article content here…', de: 'Vollständiger Artikelinhalt hier…' },
    category: 'guides',
    image: 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&h=450&fit=crop&auto=format',
    author: 'Emma Richter', date: new Date('2024-11-20'), readTime: 7, featured: true,
  },
  {
    id: 'b2',
    title: { en: 'Top 10 Fashion Deals for Black Friday 2024', de: 'Die 10 besten Mode-Deals am Black Friday 2024' },
    excerpt: { en: 'We\'ve tracked prices for 90 days to find the best genuine Black Friday fashion deals you shouldn\'t miss.', de: 'Wir haben Preise 90 Tage lang verfolgt, um die besten echten Black-Friday-Mode-Deals zu finden.' },
    content: { en: 'Full article content here…', de: 'Vollständiger Artikelinhalt hier…' },
    category: 'fashion',
    image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&h=450&fit=crop&auto=format',
    author: 'Lukas Weber', date: new Date('2024-11-18'), readTime: 5, featured: true,
  },
  {
    id: 'b3',
    title: { en: 'SEPA vs PayPal: Which Withdrawal Method is Best?', de: 'SEPA vs. PayPal: Welche Auszahlungsmethode ist besser?' },
    excerpt: { en: 'A practical comparison of withdrawal options — speed, fees, and when each is the right choice for your cashback earnings.', de: 'Ein praktischer Vergleich der Auszahlungsoptionen – Geschwindigkeit, Gebühren und wann welche die richtige Wahl ist.' },
    content: { en: 'Full article content here…', de: 'Vollständiger Artikelinhalt hier…' },
    category: 'guides',
    image: 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=800&h=450&fit=crop&auto=format',
    author: 'Sophie Keller', date: new Date('2024-11-12'), readTime: 4, featured: false,
  },
  {
    id: 'b4',
    title: { en: 'Best Travel Cashback Deals for Winter 2025', de: 'Beste Reise-Cashback-Deals für Winter 2025' },
    excerpt: { en: 'Planning a winter trip? These are the cashback rates and booking window tips that will save you the most.', de: 'Planst du eine Winterreise? Diese Cashback-Raten und Buchungstipps sparen am meisten.' },
    content: { en: 'Full article content here…', de: 'Vollständiger Artikelinhalt hier…' },
    category: 'travel',
    image: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&h=450&fit=crop&auto=format',
    author: 'Emma Richter', date: new Date('2024-11-08'), readTime: 6, featured: false,
  },
];

export const adminTransactions = [
  { id: 'at1', user: 'jan.mueller@gmail.com', store: 'Zalando', amount: 124.99, commission: 14.99, cashback: 9.99, date: new Date('2024-11-28'), status: 'approved', fraud: false },
  { id: 'at2', user: 'sarah.schmidt@web.de', store: 'Booking.com', amount: 340.00, commission: 34.00, cashback: 22.10, date: new Date('2024-11-27'), status: 'pending', fraud: false },
  { id: 'at3', user: 'max.huber@outlook.com', store: 'MediaMarkt', amount: 1299.00, commission: 77.94, cashback: 51.96, date: new Date('2024-11-26'), status: 'pending', fraud: true },
  { id: 'at4', user: 'lisa.braun@gmx.de', store: 'Douglas', amount: 67.50, commission: 12.15, cashback: 8.10, date: new Date('2024-11-25'), status: 'approved', fraud: false },
  { id: 'at5', user: 'tim.becker@t-online.de', store: 'Adidas', amount: 89.95, commission: 12.59, cashback: 8.99, date: new Date('2024-11-24'), status: 'rejected', fraud: false },
  { id: 'at6', user: 'anna.wolf@icloud.com', store: 'IKEA', amount: 445.00, commission: 22.25, cashback: 13.35, date: new Date('2024-11-23'), status: 'approved', fraud: false },
];

export const adminUsers = [
  { id: 'u1', name: 'Jan Müller', email: 'jan.mueller@gmail.com', joined: new Date('2024-01-15'), balance: 48.22, pending: 9.99, totalEarned: 156.40, status: 'active', country: 'DE' },
  { id: 'u2', name: 'Sarah Schmidt', email: 'sarah.schmidt@web.de', joined: new Date('2024-03-08'), balance: 22.10, pending: 22.10, totalEarned: 88.60, status: 'active', country: 'DE' },
  { id: 'u3', name: 'Max Huber', email: 'max.huber@outlook.com', joined: new Date('2024-05-22'), balance: 0, pending: 51.96, totalEarned: 51.96, status: 'flagged', country: 'AT' },
  { id: 'u4', name: 'Lisa Braun', email: 'lisa.braun@gmx.de', joined: new Date('2023-11-10'), balance: 74.55, pending: 8.10, totalEarned: 342.80, status: 'active', country: 'DE' },
  { id: 'u5', name: 'Tim Becker', email: 'tim.becker@t-online.de', joined: new Date('2024-07-01'), balance: 0, pending: 0, totalEarned: 0, status: 'blocked', country: 'DE' },
];

export const payoutRequests = [
  { id: 'p1', user: 'Lisa Braun', email: 'lisa.braun@gmx.de', amount: 50.00, method: 'PayPal', account: 'lisa.braun@gmx.de', requested: new Date('2024-11-28'), status: 'pending' },
  { id: 'p2', user: 'Jan Müller', email: 'jan.mueller@gmail.com', amount: 30.00, method: 'SEPA', account: 'DE89 3704 0044 0532 0130 00', requested: new Date('2024-11-26'), status: 'pending' },
  { id: 'p3', user: 'Sarah Schmidt', email: 'sarah.schmidt@web.de', amount: 22.10, method: 'PayPal', account: 'sarah.schmidt@web.de', requested: new Date('2024-11-20'), status: 'approved' },
  { id: 'p4', user: 'Anna Wolf', email: 'anna.wolf@icloud.com', amount: 13.35, method: 'SEPA', account: 'AT61 1904 3002 3457 3201', requested: new Date('2024-11-18'), status: 'approved' },
];

export const adminKpis = {
  clicks: { value: 284700, change: +12.4 },
  conversion: { value: 3.8, change: +0.3 },
  revenue: { value: 48920, change: +8.7 },
  cashbackPaid: { value: 21440, change: +11.2 },
  netProfit: { value: 27480, change: +6.8 },
};

export const chartData = [
  { month: 'Jun', revenue: 38400, cashback: 16800, clicks: 218000 },
  { month: 'Jul', revenue: 41200, cashback: 18100, clicks: 234000 },
  { month: 'Aug', revenue: 39800, cashback: 17400, clicks: 228000 },
  { month: 'Sep', revenue: 43600, cashback: 19100, clicks: 246000 },
  { month: 'Oct', revenue: 45900, cashback: 20100, clicks: 261000 },
  { month: 'Nov', revenue: 48920, cashback: 21440, clicks: 284700 },
];
