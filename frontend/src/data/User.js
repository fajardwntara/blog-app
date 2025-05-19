import { apiGet } from "@/lib/axios"

export const getCurrentUser = async () => {

    try {
        const response = await apiGet('api/user', {
            useAuth: true
        })
        if(response) return response;

    } catch (error) {
        alert("Token has been expired. Please refresh/login again.")
        sessionStorage.clear()
        window.location.href = '/login'
        console.error('Failed to fetch users:', error)
    }
}
