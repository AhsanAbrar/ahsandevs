interface AppData {
  app_name: string
  csrf_token: string
  header_logo: string | null
  prefix: string
  is_super_admin: boolean
  permissions: string[]
  translations: Record<string, string>
  user: {
    id: number
    name: string
    email: string
    avatar: string
  }
}

interface SidebarNav {
  label: string
  uri: string
  icon: FunctionalComponent<HTMLAttributes & VNodeProps>
  permission?: string
  create?: string
  createPermission?: string
  activeCollapsible?: string[]
  items?: {
    label: string
    uri: string
    permission?: string
  }[]
}

interface SettingsNav {
  label: string
  uri: string
  permission?: string
}
