function arrayUnique(arr){
  return Array.from(new Set(arr))
}
function setStorageValue(name,value){
  localStorage.setItem(name,value)
}
function getStorageValue(name){
  return localStorage.getItem(name)
}
function removeStorageValue(name){
  localStorage.removeItem(name)
}