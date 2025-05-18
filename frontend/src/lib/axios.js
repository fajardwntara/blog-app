import axios from 'axios'

const instance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

// Interceptor untuk adding token automatically,
// unless config.useAuth === false
instance.interceptors.request.use(
  (config) => {
    const token = sessionStorage.getItem('token')

    if (token && config.useAuth !== false) {
      config.headers = config.headers || {}
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  },
  (error) => Promise.reject(error)
)

// Add a response interceptor to handle token expiry
instance.interceptors.response.use(
  (response) => response, // Just return the response if successful
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      alert("Session expired. Please log in again.");
      sessionStorage.removeItem('token'); // Clear the expired token
      window.location.href = '/login'; // Redirect to login
    }
    return Promise.reject(error);
  }
);
// Handler error globally
const handleError = (method, url, error) => {
  console.error(`[${method.toUpperCase()}] ${url} failed:`, error?.response?.data || error.message)
  throw error
}

// GET request
export const apiGet = async (url, { params = {}, headers = {}, useAuth = true } = {}) => {
  try {
    const res = await instance.get(url, {
      params,
      headers,
      useAuth,
    })
    return res.data
  } catch (err) {
    handleError('get', url, err)
  }
}

// POST request
export const apiPost = async (url, data = {}, { headers = {}, useAuth = true } = {}) => {
  try {
    const res = await instance.post(url, data, {
      headers,
      useAuth,
    })
    return res.data
  } catch (err) {
    handleError('post', url, err)
  }
}

// PUT request
export const apiPut = async (url, data = {}, { headers = {}, useAuth = true } = {}) => {
  try {
    const res = await instance.put(url, data, {
      headers,
      useAuth,
    })
    return res.data
  } catch (err) {
    handleError('put', url, err)
  }
}

// DELETE request
export const apiDelete = async (url, { headers = {}, useAuth = true } = {}) => {
  try {
    const res = await instance.delete(url, {
      headers,
      useAuth,
    })
    return res.data
  } catch (err) {
    handleError('delete', url, err)
  }
}

export default instance
