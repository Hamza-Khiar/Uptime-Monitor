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
    maxlength?: {
        value: number
        applyTo: string[]
    }
    equals?: {
        applyTo: [string, string]
    }
}
export interface IForm {
    [index: string]: FormDataEntryValue | string | any
}

export type TMonitor = {
    [key: string]: string | boolean | number 
}
export type TMonitorMetadata = {
    totalMonitors:number|null,
    up:number|null,
    down:number|null,
    paused:number|null,
    sslCertCount:number|null
}
