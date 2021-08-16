import http from "../http-common";

class AuthService {
  login(name, password) {
    return http.post(`/auth/login`, {name, password});
  }
  logout() {
    return http.post(`/auth/logout`, {});
  }
}

export default new AuthService();