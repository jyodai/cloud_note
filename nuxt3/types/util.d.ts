import dateUtil from '~/utils/date';
import numberUtil from '~/utils/number';
import noteUtil from '~/utils/note';
import objectUtil from '~/utils/object';
import jsonUtil from '~/utils/json';
import sessionStorageUtil from '~/utils/sessionStorage';
import localStorageUtil from '~/utils/localStorage';

export default interface Util {
  date : dateUtil,
  number : numberUtil,
  note : noteUtil;
  object : objectUtil;
  json : jsonUtil;
  sessionStorage : sessionStorageUtil;
  localStorage : localStorageUtil;
}

