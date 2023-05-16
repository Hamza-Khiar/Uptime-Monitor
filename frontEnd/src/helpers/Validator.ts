import type { IForm } from '@/types'
class Validator {
  private form: IForm
  private equalsToArray: Array<string> = []
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
    const regExp = /^[a-zA-Z\u0020]+/u
    const result = regExp.test(this.form[field])
    if (result == false) {
      throw new Error(`the ${field} should only contain alphabetical characters`)
    }
  }
  email(field: string) {
    // eslint-disable-next-line no-useless-escape
    const regExp = /^[\w\-(\.)]+@([\w-]+\.)+[\w-]{2,4}$/
    const result = regExp.test(this.form[field])
    if (result == false) {
      const message = `Check that ${field} is a valid email`
      throw new Error(message)
    } else {
      return
    }
  }
  equals(field: string) {
    this.equalsToArray.push(this.form[field])
    if (this.equalsToArray.length !== 2) {
      return null
    } else if (this.equalsToArray[0] !== this.equalsToArray[1]) {
      throw new Error("The Confirmation & Password aren't the same")
    } else return
  }
}

export default Validator
