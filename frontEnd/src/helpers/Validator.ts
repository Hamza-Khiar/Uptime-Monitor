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
  /**
   * required will test against the field and test whether it satisfies the condition
   */
  required(field: string) {
    const regExp = /^\w{3,}/
    console.log(this.form[field])
    // try {
    //   regExp.test(field)
    // } catch (err) {
    //   console.log(err)
    // }
  }
  alpha(field: string) {
    const regExp = /^[a-zA-Z\u{0020}]+/u
  }
  email(field: string) {}
}

export default Validator
