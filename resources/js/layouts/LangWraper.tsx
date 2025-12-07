import { usePage } from '@inertiajs/react';
import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';

const LangWraper = ({ children }: { children: React.ReactNode }) => {
    const { applang } = usePage().props;
    const { i18n } = useTranslation();
    useEffect(() => {
        if (applang && i18n.language !== applang) {
            i18n.changeLanguage(applang as string);
        }
    }, [applang]);

    return <>{children}</>;
};

export default LangWraper;
