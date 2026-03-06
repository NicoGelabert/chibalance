import axiosClient from "../axios";

export function getCurrentUser({commit}, data) {
  return axiosClient.get('/user', data)
    .then(({data}) => {
      commit('setUser', data);
      return data;
    })
}

export function login({commit}, data) {
  return axiosClient.post('/login', data)
    .then(({data}) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}

export function logout({commit}) {
  return axiosClient.post('/logout')
    .then((response) => {
      commit('setToken', null)

      return response;
    })
}

export function getCountries({commit}) {
  return axiosClient.get('countries')
    .then(({data}) => {
      commit('setCountries', data)
    })
}

export function getOrders({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setOrders', [true])
  url = url || '/orders'
  const params = {
    per_page: state.orders.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setOrders', [false, response.data])
    })
    .catch(() => {
      commit('setOrders', [false])
    })
}

export function getOrder({commit}, id) {
  return axiosClient.get(`/orders/${id}`)
}

// HOMEHEROBANNERS
export function getHomeHeroBanners({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setHomeHeroBanners', [true])
  url = url || '/homeherobanners'
  const params = {
    per_page: state.homeHeroBanners.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setHomeHeroBanners', [false, response.data])
    })
    .catch(() => {
      commit('setHomeHeroBanners', [false])
    })
}

export function getHomeHeroBanner({commit}, id) {
  return axiosClient.get(`/homeherobanners/${id}`)
}

export function createHomeHeroBanner({commit}, homeHeroBanner) {
  if (homeHeroBanner.image instanceof File) {
    const form = new FormData();
    form.append('image', homeHeroBanner.image);
    form.append('headline', homeHeroBanner.headline);
    form.append('description', homeHeroBanner.description);
    form.append('link', homeHeroBanner.link);
    homeHeroBanner = form;
  }
  return axiosClient.post('/homeherobanners', homeHeroBanner)
}

export function updateHomeHeroBanner({commit}, homeHeroBanner) {
  const id = homeHeroBanner.id
  if (homeHeroBanner.image instanceof File) {
    const form = new FormData();
    form.append('id', homeHeroBanner.id);
    form.append('image', homeHeroBanner.image);
    form.append('headline', homeHeroBanner.headline);
    form.append('description', homeHeroBanner.description);
    form.append('link', homeHeroBanner.link);
    form.append('_method', 'PUT');
    homeHeroBanner = form;
  } else {
    homeHeroBanner._method = 'PUT'
  }
  return axiosClient.post(`/homeherobanners/${id}`, homeHeroBanner)
}

export function deleteHomeHeroBanner({commit}, id) {
  return axiosClient.delete(`/homeherobanners/${id}`)
}

// CATEGORIES
export function getCategories({commit, state}, {sort_field, sort_direction} = {}) {
  commit('setCategories', [true])
  return axiosClient.get('/categories', {
    params: {
      sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setCategories', [false, response.data])
    })
    .catch(() => {
      commit('setCategories', [false])
    })
}

export function createCategory({commit}, category) {
  if (category.image instanceof File) {
    const form = new FormData();
    form.append('name', category.name);
    form.append('image', category.image);
    form.append('active', category.active ? 1 : 0);
    category = form;
  }
  return axiosClient.post('/categories', category)
}

export function updateCategory({commit}, category) {
  const id = category.id
  if (category.image instanceof File) {
    const form = new FormData();
    form.append('name', category.name);
    form.append('image', category.image);
    form.append('active', category.active ? 1 : 0);
    form.append('_method', 'PUT');
    category = form;
  } else {
    category._method = 'PUT'
  }
  return axiosClient.post(`/categories/${id}`, category)
}

export function deleteCategory({commit}, id) {
  return axiosClient.delete(`/categories/${id}`)
}

// PRODUCTS
export function getProducts({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setProducts', [true])
  url = url || '/products'
  const params = {
    per_page: state.products.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setProducts', [false, response.data])
    })
    .catch(() => {
      commit('setProducts', [false])
    })
}

export function getProduct({commit}, id) {
  return axiosClient.get(`/products/${id}`)
}

export function createProduct({ commit }, product) {
  const form = new FormData();

  form.append('title', product.title);
  form.append('description', product.description || '');
  form.append('link', product.link || '');
  form.append('published', product.published ? 1 : 0);

  // Agregar precios al FormData
  if (product.prices && product.prices.length) {
    product.prices.forEach((price, index) => {
      form.append(`prices[${index}][number]`, price.number);
      form.append(`prices[${index}][size]`, price.size);
    });
  }

  // Agregar imágenes al FormData
  if (product.images && product.images.length) {
    product.images.forEach((im) => {
      form.append(`images[]`, im);
    });
  }

  // Agregar categorías al FormData
  if (product.categories && product.categories.length) {
    product.categories.forEach((category) => {
      form.append(`categories[]`, category);
    });
  }

  // Agregar beneficios al FormData
  if (product.benefits && product.benefits.length) {
    product.benefits.forEach((benefit) => {
      form.append(`benefits[]`, benefit);
    });
  }

  // Agregar cantidad al FormData
  if (product.quantity) {
    form.append('quantity', product.quantity);
  }

  return axiosClient.post('/products', form);
}


export function updateProduct({commit}, product) {
  const id = product.id
  if (product.images && product.images.length) {
    const form = new FormData();
    form.append('id', product.id);
    form.append('title', product.title);
    form.append('description', product.description || '');
    form.append('link', product.link || '');
    form.append('published', product.published ? 1 : 0);
    
  // Agregar categorías al FormData
  if (product.categories && product.categories.length) {
    product.categories.forEach((category) => {
      form.append(`categories[]`, category);
    });
  }

  // Agregar beneficios al FormData
  if (product.benefits && product.benefits.length) {
    product.benefits.forEach((benefit) => {
      form.append(`benefits[]`, benefit);
    });
  }
  
    if (product.prices && product.prices.length) {
      product.prices.forEach((price, index) => {
        form.append(`prices[${index}][number]`, price.number);
        form.append(`prices[${index}][size]`, price.size);
      });
    }
    // Agregar imágenes al FormData
    if (product.images && product.images.length) {
      product.images.forEach((im) => {
        form.append(`images[]`, im);
      });
    }
    // Agregar imágenes eliminadas al FormData
    if (product.deleted_images && product.deleted_images.length) {
      product.deleted_images.forEach((id) => form.append('deleted_images[]', id));
    }
    // Agregar posiciones de imágenes al FormData
    for (let id in product.image_positions) {
      form.append(`image_positions[${id}]`, product.image_positions[id]);
    }
    form.append('_method', 'PUT');
    product = form;
  } else {
    product._method = 'PUT'
  }
  return axiosClient.post(`/products/${id}`, product)
}

export function deleteProduct({commit}, id) {
  return axiosClient.delete(`/products/${id}`)
}

// APPOINTMENTS
// Obtener listado de citas
export function getAppointments({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
  commit('setAppointments', [true])
  url = url || '/appointments'

  const params = {
    per_page: state.appointments.limit,
  }

  return axiosClient.get(url, {
    params: {
      ...params,
      search,
      per_page,
      sort_field,
      sort_direction
    }
  })
    .then((response) => {
      commit('setAppointments', [false, response.data])
    })
    .catch(() => {
      commit('setAppointments', [false])
    })
}

// Obtener una cita específica
export function getAppointment({ commit }, id) {
  return axiosClient.get(`/appointments/${id}`)
}

// Crear una nueva cita
export function createAppointment({ commit }, appointment) {
  const form = new FormData()

  form.append('first_name', appointment.first_name)
  form.append('last_name', appointment.last_name)
  form.append('email', appointment.email)
  form.append('contact_number', appointment.contact_number)
  form.append('product_id', appointment.product_id)
  form.append('date', appointment.date)
  form.append('start_time', appointment.start_time)
  form.append('end_time', appointment.end_time || '')
  form.append('status', appointment.status || 'pending')
  form.append('notes', appointment.notes || '')
  form.append('cancel_token', appointment.cancel_token || '')

  return axiosClient.post('/appointments', form)
}

// Actualizar una cita existente
export function updateAppointment({ commit }, appointment) {
  const id = appointment.id

  const form = new FormData()
  form.append('id', id)
  form.append('first_name', appointment.first_name)
  form.append('last_name', appointment.last_name)
  form.append('email', appointment.email)
  form.append('contact_number', appointment.contact_number)
  form.append('product_id', appointment.product_id)
  form.append('date', appointment.date)
  form.append('start_time', appointment.start_time)
  form.append('end_time', appointment.end_time || '')
  form.append('status', appointment.status || 'pending')
  form.append('notes', appointment.notes || '')
  form.append('cancel_token', appointment.cancel_token || '')
  form.append('_method', 'PUT')

  return axiosClient.post(`/appointments/${id}`, form)
}

// Eliminar una cita
export function deleteAppointment({ commit }, id) {
  return axiosClient.delete(`/appointments/${id}`)
}


// ABOUT
export function getAbouts({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setAbouts', [true])
  url = url || '/abouts'
  const params = {
    per_page: state.abouts.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setAbouts', [false, response.data])
    })
    .catch(() => {
      commit('setAbouts', [false])
    })
}


export function getAbout({commit}, id) {
  return axiosClient.get(`/abouts/${id}`)
}


export function createAbout({commit}, about) {
  if (about.image instanceof File) {
    const form = new FormData();
    form.append('image', about.image);
    form.append('headline', about.headline);
    form.append('short_description', about.short_description);
    form.append('large_description', about.large_description);
    form.append('signature', about.signature);
    form.append('mission_and_vision', about.mission_and_vision);
    about = form;
  }
  return axiosClient.post('/abouts', about)
}


export function updateAbout({commit}, about) {
  const id = about.id
  if (about.image instanceof File) {
    const form = new FormData();
    form.append('id', about.id);
    form.append('image', about.image);
    form.append('headline', about.headline);
    form.append('short_description', about.short_description);
    form.append('large_description', about.large_description);
    form.append('signature', about.signature);
    form.append('mission_and_vision', about.mission_and_vision);
    form.append('_method', 'PUT');
    about = form;
  } else {
    about._method = 'PUT'
  }
  return axiosClient.post(`/abouts/${id}`, about)
}


export function deleteAbout({commit}, id) {
  return axiosClient.delete(`/abouts/${id}`)
}

// ARTICLES
export function getArticles({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setArticles', [true])
  url = url || '/articles'
  const params = {
    per_page: state.articles.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setArticles', [false, response.data])
    })
    .catch(() => {
      commit('setArticles', [false])
    })
}

export function getArticle({commit}, id) {
  return axiosClient.get(`/articles/${id}`)
}

export function createArticle({ commit }, article) {
  const form = new FormData();

  form.append('title', article.title);
  form.append('subtitle', article.subtitle || '');
  form.append('news_lead', article.news_lead || '');
  form.append('description', article.description || '');
  form.append('published', article.published ? 1 : 0);

  // Agregar imágenes al FormData
  if (article.images && article.images.length) {
    article.images.forEach((im) => {
      form.append(`images[]`, im);
    });
  }

  // Agregar alérgenos al FormData
  if (article.authors && article.authors.length) {
    article.authors.forEach((author) => {
      form.append(`authors[]`, author);
    });
  }
  
  return axiosClient.post('/articles', form);
}


export function updateArticle({commit}, article) {
  const id = article.id
  if (article.images && article.images.length) {
    const form = new FormData();
    form.append('id', article.id);
    form.append('title', article.title);
    form.append('subtitle', article.subtitle || '');
    form.append('news_lead', article.news_lead || '');
    form.append('description', article.description || '');
    form.append('published', article.published ? 1 : 0);

  // Agregar alérgenos al FormData
  if (article.authors && article.authors.length) {
    article.authors.forEach((author) => {
      form.append(`authors[]`, author);
    });
  }
    // Agregar imágenes al FormData
    if (article.images && article.images.length) {
      article.images.forEach((im) => {
        form.append(`images[]`, im);
      });
    }
    // Agregar imágenes eliminadas al FormData
    if (article.deleted_images && article.deleted_images.length) {
      article.deleted_images.forEach((id) => form.append('deleted_images[]', id));
    }
    // Agregar posiciones de imágenes al FormData
    for (let id in article.image_positions) {
      form.append(`image_positions[${id}]`, article.image_positions[id]);
    }
    form.append('_method', 'PUT');
    article = form;
  } else {
    article._method = 'PUT'
  }
  return axiosClient.post(`/articles/${id}`, article)
}

export function deleteArticle({commit}, id) {
  return axiosClient.delete(`/articles/${id}`)
}

// AUTHOR
export function getAuthors({commit, state}, {sort_field, sort_direction} = {}) {
  commit('setAuthors', [true])
  return axiosClient.get('/authors', {
    params: {
      sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setAuthors', [false, response.data])
    })
    .catch(() => {
      commit('setAuthors', [false])
    })
}

export function getAuthor({commit}, id) {
  return axiosClient.get(`/authors/${id}`)
}

export function createAuthor({commit}, author) {
  if (author.image instanceof File) {
    const form = new FormData();
    form.append('name', author.name);
    form.append('image', author.image);
    form.append('description', author.description);
    form.append('active', author.active ? 1 : 0);
    author = form;
  }
  return axiosClient.post('/authors', author)
}


export function updateAuthor({commit}, author) {
  const id = author.id
  if (author.image instanceof File) {
    const form = new FormData();
    form.append('id', author.id);
    form.append('name', author.name);
    form.append('image', author.image);
    form.append('description', author.description);
    form.append('active', author.active ? 1 : 0);
    form.append('_method', 'PUT');
    author = form;
  } else {
    author._method = 'PUT'
  }
  return axiosClient.post(`/authors/${id}`, author)
}


export function deleteAuthor({commit}, id) {
  return axiosClient.delete(`/authors/${id}`)
}

// USERS
export function getUsers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setUsers', [true])
  url = url || '/users'
  const params = {
    per_page: state.users.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setUsers', [false, response.data])
    })
    .catch(() => {
      commit('setUsers', [false])
    })
}

export function createUser({commit}, user) {
  return axiosClient.post('/users', user)
}

export function updateUser({commit}, user) {
  return axiosClient.put(`/users/${user.id}`, user)
}

export function getCustomers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setCustomers', [true])
  url = url || '/customers'
  const params = {
    per_page: state.customers.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setCustomers', [false, response.data])
    })
    .catch(() => {
      commit('setCustomers', [false])
    })
}

export function getCustomer({commit}, id) {
  return axiosClient.get(`/customers/${id}`)
}

export function createCustomer({commit}, customer) {
  return axiosClient.post('/customers', customer)
}

export function updateCustomer({commit}, customer) {
  return axiosClient.put(`/customers/${customer.id}`, customer)
}

export function deleteCustomer({commit}, customer) {
  return axiosClient.delete(`/customers/${customer.id}`)
}

// SERVICES
export function getServices({commit, state}, {sort_field, sort_direction} = {}) {
  commit('setServices', [true])
  return axiosClient.get('/services', {
    params: {
      sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setServices', [false, response.data])
    })
    .catch(() => {
      commit('setServices', [false])
    })
}

export function createService({commit}, service) {
  if (service.image instanceof File) {
    const form = new FormData();
    form.append('name', service.name);
    form.append('icon', service.icon);
    form.append('active', service.active ? 1 : 0);
    form.append('short_description', service.short_description);
    form.append('description', service.description);
    // Agregar atributos al FormData
    if (service.attributes && service.attributes.length) {
      service.attributes.forEach((attribute, index) => {
        form.append(`attributes[${index}][text]`, attribute.text);
      });
    }
    form.append('image', service.image);
    service = form;
  }
  return axiosClient.post('/services', service)
}

export function updateService({commit}, service) {
  const id = service.id
  if (service.image instanceof File) {
    const form = new FormData();
    form.append('name', service.name);
    form.append('icon', service.icon);
    form.append('active', service.active ? 1 : 0);
    form.append('short_description', service.short_description);
    form.append('description', service.description);
    // Agregar atributos al FormData
    if (service.attributes && service.attributes.length) {
      service.attributes.forEach((attribute, index) => {
        form.append(`attributes[${index}][text]`, attribute.text);
      });
    }
    form.append('image', service.image);
    form.append('_method', 'PUT');
    service = form;
  } else {
    service._method = 'PUT'
  }
  return axiosClient.post(`/services/${id}`, service)
}

export function deleteService({commit}, service) {
  return axiosClient.delete(`/services/${service.id}`)
}

// PROJECTS
export function getProjects({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setProjects', [true])
  url = url || '/projects'
  const params = {
    per_page: state.projects.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setProjects', [false, response.data])
    })
    .catch(() => {
      commit('setProjects', [false])
    })
}

export function getProject({commit}, id) {
  return axiosClient.get(`/projects/${id}`)
}

export function createProject({ commit }, project) {
  const form = new FormData();

  form.append('title', project.title);
  form.append('description', project.description || '');
  form.append('published', project.published ? 1 : 0);

  // Agregar imágenes al FormData
  if (project.images && project.images.length) {
    project.images.forEach((im) => {
      form.append(`images[]`, im);
    });
  }

  // Agregar services al FormData
  if (project.services && project.services.length) {
    project.services.forEach((service) => {
      form.append(`services[]`, service);
    });
  }

  // Agregar tags al FormData
  if (project.tags && project.tags.length) {
    project.tags.forEach((tag) => {
      form.append(`tags[]`, tag);
    });
  }

  // Agregar clients al FormData
  if (project.clients && project.clients.length) {
    project.clients.forEach((client) => {
      form.append(`clients[]`, client);
    });
  }

  return axiosClient.post('/projects', form);
}


export function updateProject({commit}, project) {
  const id = project.id
  if (project.images && project.images.length) {
    const form = new FormData();
    form.append('id', project.id);
    form.append('title', project.title);
    form.append('description', project.description || '');
    form.append('published', project.published ? 1 : 0);
    
    // Agregar services al FormData
    if (project.services && project.services.length) {
      project.services.forEach((service) => {
        form.append(`services[]`, service);
      });
    }

    // Agregar tags al FormData
    if (project.tags && project.tags.length) {
      project.tags.forEach((tag) => {
        form.append(`tags[]`, tag);
      });
    }

    // Agregar clients al FormData
    if (project.clients && project.clients.length) {
      project.clients.forEach((client) => {
        form.append(`clients[]`, client);
      });
    }

    // Agregar imágenes al FormData
    if (project.images && project.images.length) {
      project.images.forEach((im) => {
        form.append(`images[]`, im);
      });
    }

    // Agregar imágenes eliminadas al FormData
    if (project.deleted_images && project.deleted_images.length) {
      project.deleted_images.forEach((id) => form.append('deleted_images[]', id));
    }
    
    // Agregar posiciones de imágenes al FormData
    for (let id in project.image_positions) {
      form.append(`image_positions[${id}]`, project.image_positions[id]);
    }
    form.append('_method', 'PUT');
    project = form;
  } else {
    project._method = 'PUT'
  }
  return axiosClient.post(`/projects/${id}`, project)
}

export function deleteProject({commit}, id) {
  return axiosClient.delete(`/projects/${id}`)
}

//TAGS
export function getTags({commit, state}, {sort_field, sort_direction} = {}) {
  commit('setTags', [true])
  return axiosClient.get('/tags', {
    params: {
      sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setTags', [false, response.data])
    })
    .catch(() => {
      commit('setTags', [false])
    })
}

export function createTag({commit}, tag) {
  if (tag.image instanceof File) {
    const form = new FormData();
    form.append('name', tag.name);
    form.append('image', tag.image);
    form.append('active', tag.active ? 1 : 0);
    tag = form;
  }
  return axiosClient.post('/tags', tag)
}

export function updateTag({commit}, tag) {
  const id = tag.id
  if (tag.image instanceof File) {
    const form = new FormData();
    form.append('name', tag.name);
    form.append('image', tag.image);
    form.append('active', tag.active ? 1 : 0);
    form.append('_method', 'PUT');
    tag = form;
  } else {
    tag._method = 'PUT'
  }
  return axiosClient.post(`/tags/${id}`, tag)
}

export function deleteTag({commit}, tag) {
  return axiosClient.delete(`/tags/${tag.id}`)
}

//CLIENTS
export function getClients({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setClients', [true])
  url = url || '/clients'
  const params = {
    per_page: state.clients.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setClients', [false, response.data])
    })
    .catch(() => {
      commit('setClients', [false])
    })
}

export function getClient({commit}, id) {
  return axiosClient.get(`/clients/${id}`)
}

export function createClient({commit}, client) {
  if (client.image instanceof File) {
    const form = new FormData();
    form.append('full_name', client.full_name);
    form.append('age', client.age);
    form.append('phone_number', client.phone_number);
    form.append('emergency_phone_number', client.emergency_phone_number);
    form.append('town', client.town);
    form.append('occupancy', client.occupancy);
    form.append('email', client.email);
    form.append('treatment', client.treatment);
    form.append('sore', client.sore);
    form.append('medication', client.medication);
    form.append('allergies', client.allergies);
    form.append('medicalBackground', client.medicalBackground);
    form.append('sports', client.sports);
    form.append('currentDiet', client.currentDiet);
    form.append('sleepPatterns', client.sleepPatterns);
    form.append('waterIntake', client.waterIntake);
    form.append('pregnancy', client.pregnancy);
    form.append('menopause', client.menopause);
    form.append('signed', client.signed ? 1 : 0);
    client = form;
  }
  return axiosClient.post('/clients', client)
}

export function updateClient({commit}, client) {
  const id = client.id
  if (client.image instanceof File) {
    const form = new FormData();
    form.append('full_name', client.full_name);
    form.append('age', client.age);
    form.append('phone_number', client.phone_number);
    form.append('emergency_phone_number', client.emergency_phone_number);
    form.append('town', client.town);
    form.append('occupancy', client.occupancy);
    form.append('email', client.email);
    form.append('treatment', client.treatment);
    form.append('sore', client.sore);
    form.append('medication', client.medication);
    form.append('allergies', client.allergies);
    form.append('medicalBackground', client.medicalBackground);
    form.append('sports', client.sports);
    form.append('currentDiet', client.currentDiet);
    form.append('sleepPatterns', client.sleepPatterns);
    form.append('waterIntake', client.waterIntake);
    form.append('pregnancy', client.pregnancy);
    form.append('menopause', client.menopause);
    form.append('signed', client.signed ? 1 : 0);
    client = form;
  } else {
    client._method = 'PUT'
  }
  return axiosClient.post(`/clients/${id}`, client)
}

export function deleteClient({commit}, client) {
  return axiosClient.delete(`/clients/${client.id}`)
}