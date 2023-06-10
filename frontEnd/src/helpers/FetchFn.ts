/**
 *	this will be a fetch wrapper will have a default header and will take Method,Body
 */
import { AUTH_CSRF_COOKIE_URL } from '@/env'

const defaultHeader: { [key: string]: string } = {
    Accept: 'application/json',
    'Content-Type': 'application/json'
}

async function cookieExists(csrfRequired: boolean) {
    if (csrfRequired) {
        await fetch(AUTH_CSRF_COOKIE_URL, {
            method: 'GET',
            credentials: 'include'
        })
        Object.assign(defaultHeader, {
            'X-XSRF-TOKEN': decodeURIComponent(document.cookie).slice(
                decodeURIComponent(document.cookie).indexOf('=') + 1
            )
        })
    } else {
        delete defaultHeader['X-XSRF-TOKEN'];
    }
}


export const fetchFn = async function(
    resource: string,
    method: 'GET' | 'POST' | 'PUT' | 'DELETE' = 'GET',
    csrfRequired: boolean,
    Body?: BodyInit,
) {
    await cookieExists(csrfRequired)
    const response = await fetch(resource, {
        method: method,
        headers: defaultHeader,
        body: Body,
        credentials: 'include'
    })
    return response
}
