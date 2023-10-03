export default {
  percent (num : number): string {
    return new Intl.NumberFormat(
      'ja',
      { style : 'percent'}
    ).format(num);
  },
};
