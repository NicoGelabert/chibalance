
export function setUser(state, user) {
  state.user.data = user;
}

export function setToken(state, token) {
  state.user.token = token;
  if (token) {
    sessionStorage.setItem('TOKEN', token);
  } else {
    sessionStorage.removeItem('TOKEN')
  }
}

export function setHomeHeroBanners(state, [loading, data = null]) {

  if (data) {
    state.homeHeroBanners = {
      ...state.homeHeroBanners,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.homeHeroBanners.loading = loading;
}

export function setCategories(state, [loading, data = null]) {

  if (data) {
    state.categories = {
      ...state.categories,
      data: data.data,
    }
  }

  state.categories.loading = loading;
}

export function setProducts(state, [loading, data = null]) {

  if (data) {
    state.products = {
      ...state.products,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

export function setAppointments(state, [loading, data = null]) {
  if (data) {
    state.appointments = {
      ...state.appointments,
      data: data.data,
      links: data.meta?.links,
      page: data.meta?.current_page,
      limit: data.meta?.per_page,
      from: data.meta?.from,
      to: data.meta?.to,
      total: data.meta?.total,
    }
  }
  state.appointments.loading = loading
}

export function setAbouts(state, [loading, data = null]) {

  if (data) {
    state.abouts = {
      ...state.abouts,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.abouts.loading = loading;
}

export function setArticles(state, [loading, data = null]) {

  if (data) {
    state.articles = {
      ...state.articles,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.articles.loading = loading;
}

export function setAuthors(state, [loading, data = null]) {

  if (data) {
    state.authors = {
      ...state.authors,
      data: data.data,
    }
  }
  state.authors.loading = loading;
}

export function setUsers(state, [loading, data = null]) {

  if (data) {
    state.users = {
      ...state.users,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

export function setCustomers(state, [loading, data = null]) {

  if (data) {
    state.customers = {
      ...state.customers,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

export function setOrders(state, [loading, data = null]) {

  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.orders.loading = loading;
}

export function showToast(state, message) {
  state.toast.show = true;
  state.toast.message = message;
}

export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}

export function setCountries(state, countries) {
  state.countries = countries.data;
}

export function setAlergens(state, [loading, data = null]) {

  if (data) {
    state.alergens = {
      ...state.alergens,
      data: data.data,
    }
  }

  state.alergens.loading = loading;
}

export function setServices(state, [loading, data = null]) {

  if (data) {
    state.services = {
      ...state.services,
      data: data.data,
    }
  }

  state.services.loading = loading;
}

export function setProjects(state, [loading, data = null]) {

  if (data) {
    state.projects = {
      ...state.projects,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.projects.loading = loading;
}

export function setTags(state, [loading, data = null]) {

  if (data) {
    state.tags = {
      ...state.tags,
      data: data.data,
    }
  }

  state.tags.loading = loading;
}

export function setClients(state, [loading, data = null]) {

  if (data) {
    state.clients = {
      ...state.clients,
      data: data.data,
    }
  }

  state.clients.loading = loading;
}