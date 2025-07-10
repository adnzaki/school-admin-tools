declare module '*.vue' {
  import { DefineComponent } from 'vue'
  // @ts-expect-error: Needed to support legacy component typing
  const component: DefineComponent<unknown, unknown, Record<string, unknown>>
  export default component
}
