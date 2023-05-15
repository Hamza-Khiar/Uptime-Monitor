import type { IForm, Rules } from '@/types'
import Validator from './Validator'

export function FormValidate(form: IForm, rulesToApply: Rules) {
  const formToTest: { [key: string]: any } = new Validator(form)
  const validateCapture: string[][] = []

  for (const key in rulesToApply) {
    rulesToApply[key as keyof Rules]?.applyTo.forEach((field: string) => {
      try {
        formToTest[key](field)
      } catch (err: any) {
        validateCapture.push([field, err])
      }
    })
  }
  return validateCapture
}
