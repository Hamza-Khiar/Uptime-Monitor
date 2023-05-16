import type { IForm, Rules } from '@/types'
import Validator from './Validator'

export function formValidate(form: IForm, rulesToApply: Rules) {
  const formToTest: { [key: string]: any } = new Validator(form)
  const validateCapture: object[] = []
  const extraArgs: any = {}
  for (const key in rulesToApply) {
    extraArgs.minlength = rulesToApply.minlength?.value
    rulesToApply[key as keyof Rules]?.applyTo.forEach((field: string) => {
      const errorsObj: IForm = {}
      try {
        formToTest[key](field, extraArgs.minlength)
      } catch (err: any) {
        errorsObj[field] = err
        validateCapture.push(errorsObj)
      }
    })
  }
  return validateCapture
}
