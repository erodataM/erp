import http from "../http-common";

class DataService {
  getPaginate(entity, page, perPage) {
    return http.get(`/api/list/${entity}/${page}/${perPage}`);
  }
  getSelect(entity) {
    return http.get(`/select/list/${entity}`);
  }
  get(entity, id) {
    return http.get(`/api/get/${entity}/${id}`);
  }
  edit(entity, id, data) {
    return http.put(`/api/edit/${entity}/${id}`, data);
  }
  add(entity, data) {
    return http.post(`/api/add/${entity}`, data);
  }
  delete(entity, id) {
    return http.delete(`/api/delete/${entity}/${id}`);
  }
}

export default new DataService();