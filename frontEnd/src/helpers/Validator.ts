import type { IForm } from '@/types'
class Validator {
  private form: IForm
  /**
   * taking the form Object and run some Checks in it
   */
  constructor(form: IForm) {
    this.form = form
  }
  minlength(field: string, length: number) {
    if (this.form[field].length < length) {
      const message = `${field} should contain at least ${length} characters long`
      throw new Error(message)
    } else return
  }
  required(field: string) {
    const regExp = /^\w/
    const result = regExp.test(this.form[field])
    if (result == false) {
      const message = `${field} is Empty`
      throw new Error(message)
    } else {
      return
    }
  }
  alpha(field: string) {
    const regExp = /^[a-zA-Z\u{0020}]+/u
  }
  email(field: string) {
    const regExp = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/
    const result = regExp.test(this.form[field])
    if (result == false) {
      const message = `Check that ${field} is a valid email`
      throw new Error(message)
    } else {
      return
    }
  }
}

export default Validator
