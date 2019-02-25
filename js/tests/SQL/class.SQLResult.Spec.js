/**
 * @file Test specification for SQL/class.SQLResult.js
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version 0.1
 */

describe("A SQLResult", function() {
  it("can be converted to a valid JSON string", function() {
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "3", "4", "5"],
                                            ["3", "4", "5", "6"]]});

    // Create the expected JSON string
    var expectedJSON = "{\"columns\":[\"A\",\"B\",\"C\",\"D\"],\"values\":[[\"1\",\"2\",\"3\",\"4\"],[\"2\",\"3\",\"4\",\"5\"],[\"3\",\"4\",\"5\",\"6\"]]}";

    // Do the test
    expect(sqlResult.toJSON()).toBe(expectedJSON);
  });

  it("returns the correct number of rows", function() {
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "3", "4", "5"],
                                            ["3", "4", "5", "6"]]});

    // Do the test
    expect(sqlResult.getNumberOfRows()).toBe(3);
  });

  it("is able to checkFunctionalDependencies", function() {
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "2", "3", "5"],
                                            ["3", "2", "5", "6"]]});

    // Do the test(s)
    expect(sqlResult.checkFunctionalDependency(["A"], ["B"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["B"], ["A"])).toBe(false);
    expect(sqlResult.checkFunctionalDependency(["C"], ["A"])).toBe(false);
    expect(sqlResult.checkFunctionalDependency(["C"], ["B"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["D"], ["A"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["A", "B"], ["C"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["C"], ["B", "A"])).toBe(false);
  });

  it("is able to transform column names to positions", function() {
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "3", "4", "5"],
                                            ["3", "4", "5", "6"]]});

    // Do the test(s)
    expect(sqlResult.transformColumnNamesToPositions(["A"])).toEqual([0]);
    expect(sqlResult.transformColumnNamesToPositions(["C"])).toEqual([2]);
    expect(sqlResult.transformColumnNamesToPositions(["A", "C"])).toEqual([0, 2]);
    expect(sqlResult.transformColumnNamesToPositions(["B", "D"])).toEqual([1, 3]);
  });

});
