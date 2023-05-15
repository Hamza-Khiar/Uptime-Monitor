export type Rules = {
  required?: {
    applyTo: string[]
  }
  email?: {
    applyTo: string[]
  }
  alpha?: {
    applyTo: string[]
  }
  minlength?: {
    value: number
    applyTo: string[]
  }
}
export interface IForm {
  [index: string]: FormDataEntryValue | string | any
}

