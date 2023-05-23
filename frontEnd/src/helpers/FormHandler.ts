import type { IForm, Rules } from '@/types'
import Validator from './Validator'

export function formValidate(form: IForm, rulesToApply: Rules) {
  const formToTest: { [key: string]: any } = new Validator(form)
  const validateCapture: object[] = []
  const extraArgs: any = {}
  for (const key in rulesToApply) {
    extraArgs.minlength = rulesToApply.minlength?.value
    extraArgs.maxlength = rulesToApply.maxlength?.value
    rulesToApply[key as keyof Rules]?.applyTo.forEach((field: string) => {
      const errorsObj: IForm = {}
      try {
        if (key == 'minlength') {
          formToTest[key](field, extraArgs?.minlength)
        }
        if (key == 'maxlength') {
          formToTest[key](field, extraArgs?.maxlength)
        } else {
          formToTest[key](field)
        }
      } catch (err: any) {
        errorsObj[field] = err
        validateCapture.push(errorsObj)
      }
    })
  }
  return validateCapture
}
