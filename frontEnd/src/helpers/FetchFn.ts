/**
 *	this will be a ofetch wrapper will have a default header and will take Method,Body
 */
const defaultHeader: { [key: string]: string } = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  credentials: 'include'
}

function cookieExists() {
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
  cookieExists()
  const response = await fetch(resource, {
    method: method,
    headers: defaultHeader,
    body: Body
  })
  return response
}
