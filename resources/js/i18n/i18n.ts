import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import HttpBackend from 'i18next-http-backend';
import LanguageDetector from 'i18next-browser-languagedetector';

i18n
  .use(HttpBackend) // لتحميل ملفات الترجمة
  .use(LanguageDetector) // لاكتشاف لغة المستخدم
  .use(initReactI18next)
  .init({
    fallbackLng: 'ar', // اللغة الافتراضية
    debug: true,
    interpolation: {
      escapeValue: false,
    },
    backend: {
      loadPath: '/locales/{{lng}}/{{lng}}.json', // مكان ملفات الترجمة
    },
  });

export default i18n;
