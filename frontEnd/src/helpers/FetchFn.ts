/**
 *	this will be a fetch wrapper will have a default header and will take Method,Body
 */
import { AUTH_CSRF_COOKIE_URL } from '@/env'

const defaultHeader: { [key: string]: string } = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  credentials: 'include'
}

async function cookieExists() {
  if (!document.cookie) {
    await fetch(AUTH_CSRF_COOKIE_URL, {
      method: 'GET',
      credentials: 'include'
    })
  }
  if (document.cookie && !defaultHeader?.['X-XSRF-TOKEN']) {
    Object.assign(defaultHeader, {
      'X-XSRF-TOKEN': document.cookie.split('=')[1]
    })
    console.log(defaultHeader)
  }
}

export const fetchFn = async function (
  resource: string,
  method: 'GET' | 'POST' | 'PUT' | 'DELETE',
  Body: BodyInit
) {
  await cookieExists()
  const response = await fetch(resource, {
    method: method,
    headers: defaultHeader,
    body: Body
  })
  return response
}
