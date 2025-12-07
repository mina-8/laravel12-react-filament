import React from 'react'
import { Link, usePage } from '@inertiajs/react'
import { Globe } from 'lucide-react';
// import { AiOutlineGlobal } from 'react-icons/ai';

interface Props {
    currentRoute?: string;
    slugs?: Record<string, string>;
    applang?: string;
    [key: string]: unknown;
}

const ChangeLang = () => {
    const { url } = usePage();
    const { slugs, applang } = usePage<Props>().props;

    // ✅ استخدام اللغة من Laravel بدلاً من استخراجها من الرابط
    const currentLang = applang ?? 'en';
    const targetLang = currentLang === 'en' ? 'ar' : 'en';

    // ✅ إنشاء الرابط الجديد بناءً على اللغة المستهدفة
    const createLangUrl = (): string => {
        const urlParts = url.split('/').filter(Boolean);

        // إذا كان هناك slug محدد للغة المستهدفة
        if (slugs && slugs[targetLang] && slugs[currentLang]) {
            // استبدال اللغة والـ slug
            return url
                .replace(`/${currentLang}/`, `/${targetLang}/`)
                .replace(slugs[currentLang], slugs[targetLang]);
        }

        // استبدال اللغة فقط في الرابط
        if (urlParts[0] === currentLang) {
            urlParts[0] = targetLang;
            return '/' + urlParts.join('/');
        }

        // إضافة اللغة إذا لم تكن موجودة
        return `/${targetLang}${url}`;
    };

    const langUrl = createLangUrl();

    return (
        <Link
            href={langUrl}
            className="flex justify-center items-center gap-2 px-4  hover:opacity-80 transition-opacity"
            preserveScroll
        >
            
            <Globe />
            <span className="font-medium">
                {targetLang === 'en' ? 'English' : 'عربي'}
            </span>
        </Link>
    )
}

export default ChangeLang
