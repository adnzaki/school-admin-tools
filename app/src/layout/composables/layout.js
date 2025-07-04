import { computed, reactive } from 'vue'

const layoutConfig = reactive({
  preset: localStorage.getItem('preset') || 'Aura',
  primary: localStorage.getItem('primary') || 'emerald',
  surface: localStorage.getItem('surface') || null,
  darkTheme: JSON.parse(localStorage.getItem('darkTheme')) || false,
  menuMode: localStorage.getItem('menuMode') || 'static'
})

const layoutState = reactive({
  staticMenuDesktopInactive: false,
  overlayMenuActive: false,
  profileSidebarVisible: false,
  configSidebarVisible: false,
  staticMenuMobileActive: false,
  menuHoverActive: false,
  activeMenuItem: null
})

export function useLayout() {
  const setActiveMenuItem = (item) => {
    layoutState.activeMenuItem = item.value || item
  }

  const toggleDarkMode = () => {
    if (!document.startViewTransition) {
      executeDarkModeToggle()

      return
    }

    document.startViewTransition(() => executeDarkModeToggle(event))
  }

  const executeDarkModeToggle = () => {
    layoutConfig.darkTheme = !layoutConfig.darkTheme
    document.documentElement.classList.toggle('app-dark')

    localStorage.setItem('darkTheme', JSON.stringify(layoutConfig.darkTheme))
  }

  const toggleMenu = () => {
    if (layoutConfig.menuMode === 'overlay') {
      layoutState.overlayMenuActive = !layoutState.overlayMenuActive
    }

    if (window.innerWidth > 991) {
      layoutState.staticMenuDesktopInactive = !layoutState.staticMenuDesktopInactive
    } else {
      layoutState.staticMenuMobileActive = !layoutState.staticMenuMobileActive
    }
  }

  const isSidebarActive = computed(() => layoutState.overlayMenuActive || layoutState.staticMenuMobileActive)

  const isDarkTheme = computed(() => layoutConfig.darkTheme)

  const getPrimary = computed(() => layoutConfig.primary)

  const getSurface = computed(() => layoutConfig.surface)

  return {
    layoutConfig,
    layoutState,
    toggleMenu,
    isSidebarActive,
    isDarkTheme,
    getPrimary,
    getSurface,
    setActiveMenuItem,
    toggleDarkMode
  }
}
