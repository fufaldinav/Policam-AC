export function trans(key, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations);

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}

export function transChoice(key, count = 1, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations).split('|');

    translation = count > 1 ? translation[1] : translation[0];

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}
