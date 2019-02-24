import '../../classes/SQL/class.SQLResult.js';

test('SQLResult to JSON', () => {
  // First we construct a SQLResult object
  var result = new SQLResult({columns: ["A", "B", "C", "D"],
                              values: [["1", "2", "3", "4"],
                                       ["2", "3", "4", "5"],
                                       ["3", "4", "5", "6"]]});

  var expectedJSONString = "{\"columns\":[\"A\",\"B\",\"C\",\"D\"],\"values\":[[\"1\",\"2\",\"3\",\"4\"],[\"2\",\"3\",\"4\",\"5\"],[\"3\",\"4\",\"5\",\"6\"]]}";

  expect(result.toJSON()).toBe(expectedJSONString);
})
