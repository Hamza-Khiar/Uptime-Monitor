import type { IForm } from '@/types'
class Validator {
  private form: IForm
  /**
   * taking the form Object and run some Checks in it
   */
  constructor(form: IForm) {
    this.form = form
  }
  minLength(field: string, length: number) {
    // const regExpStr = `/.{${length},}/`
    // let regExp = new RegExp(regExpStr)
    // regExp.test()
  }
  required(field: string) {
    const regExp = /^\w{3,}/
    const result = regExp.test(this.form[field])
    if (result == false) {
      const message = `${field} should contain at least 3 characters long`
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
