export const saveToken = (token) => {
  localStorage.setItem("jwt_token", token);
};

export const getToken = () => {
  return localStorage.getItem("jwt_token");
};

export const removeToken = () => {
  localStorage.removeItem("jwt_token");
};

export const decodeToken = (token) => {
  try {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace("-", "+").replace("_", "/");
    return JSON.parse(window.atob(base64));
  } catch (e) {
    return null;
  }
};
