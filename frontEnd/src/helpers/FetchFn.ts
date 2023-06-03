/**
 *	this will be a fetch wrapper will have a default header and will take Method,Body
 */
import { AUTH_CSRF_COOKIE_URL } from '@/env'

const defaultHeader: { [key: string]: string } = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  credentials: 'include'
}

async function cookieExists(csrfRequired: boolean) {
  if (csrfRequired) {
    if (!document.cookie) {
      await fetch(AUTH_CSRF_COOKIE_URL, {
        method: 'GET',
        credentials: 'include'
      })
    }
    if (document.cookie && !defaultHeader?.['X-XSRF-TOKEN']) {
      Object.assign(defaultHeader, {
        'X-XSRF-TOKEN': decodeURIComponent(document.cookie).slice(
          decodeURIComponent(document.cookie).indexOf('=') + 1
        )
      })
      console.log(
        defaultHeader,
        decodeURIComponent(document.cookie).slice(decodeURIComponent(document.cookie).indexOf('='))
      )
    }
  }
}

export const fetchFn = async function (
  resource: string,
  method: 'GET' | 'POST' | 'PUT' | 'DELETE',
  Body: BodyInit,
  csrfRequired: boolean
) {
  await cookieExists(csrfRequired)

  const response = await fetch(resource, {
    method: method,
    headers: defaultHeader,
    body: Body
  })
  return response
}
