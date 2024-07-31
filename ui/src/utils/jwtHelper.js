// jwtHelper.js

// Save token to localStorage
export const saveToken = (token) => {
  localStorage.setItem("jwt_token", token);
};

// Get token from localStorage
export const getToken = () => {
  return localStorage.getItem("jwt_token");
};

// Remove token from localStorage
export const removeToken = () => {
  localStorage.removeItem("jwt_token");
};

// Decode the token without verifying the signature
export const decodeToken = (token) => {
  try {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    return JSON.parse(window.atob(base64));
  } catch (e) {
    return null;
  }
};

// Check if the token is expired
export const isTokenExpired = (token) => {
  const decodedToken = decodeToken(token);
  if (!decodedToken) return true;

  const currentTime = Math.floor(Date.now() / 1000);
  return decodedToken.exp < currentTime;
};

// Example usage of verifying a token on the server side
// Note: This requires server-side code to actually verify
export const verifyTokenServerSide = async (token) => {
  try {
    const response = await fetch("/api/verify-token", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ token }),
    });

    const data = await response.json();
    return data.isValid; // Assuming the server responds with { isValid: true/false }
  } catch (error) {
    console.error("Error verifying token:", error);
    return false;
  }
};
