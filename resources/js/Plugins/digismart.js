export function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

export async function downloadFile(url, fileName) {

    await axios.get(url, { responseType: 'blob'})
        .then(response => {
            const aElement = document.createElement('a')
            aElement.setAttribute('download', fileName)
            const href = URL.createObjectURL(response.data)
            aElement.href = href
            aElement.setAttribute('target', '_blank')
            aElement.click()
            URL.revokeObjectURL(href)
        })
        .catch(response => {
            throw new Error(response.response.data.message)
        })
}