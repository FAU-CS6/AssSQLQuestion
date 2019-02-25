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
    /*
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "3", "4", "5"],
                                            ["3", "4", "5", "6"]]});

    // Do the test
    expect(sqlResult.getNumberOfRows()).toBe(3);
  });

  it("is able to get all functional dependencies", function() {
    // Create one valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "2", "3", "5"],
                                            ["3", "2", "5", "6"]]});

    // Do the test - there should be 7 functional dependencies (A->BCD, C->D, D->ABC)
    // Of course there are even more functional dependencies in the example. But these are the minimal ones.
    // (e.g. There is AB->CD, too - But A->BCD allready proves this)
    expect(sqlResult.getAllFunctionalDependencies()).toEqual([{determinate:['A'], dependent:['B']},
                                                              {determinate:['A'], dependent:['C']},
                                                              {determinate:['A'], dependent:['D']},
                                                              {determinate:['C'], dependent:['B']},
                                                              {determinate:['D'], dependent:['A']},
                                                              {determinate:['D'], dependent:['B']},
                                                              {determinate:['D'], dependent:['C']}]);

    // Second test
    var sqlResult2 = new SQLResult({columns: ["A", "B", "C", "D"],
                                    values: [["1", "2", "3", "1"],
                                             ["2", "2", "3", "5"],
                                             ["3", "2", "5", "5"]]});


    expect(sqlResult2.getAllFunctionalDependencies()).toEqual([{determinate:['A'], dependent:['B']},
                                                               {determinate:['A'], dependent:['C']},
                                                               {determinate:['A'], dependent:['D']},
                                                               {determinate:['C'], dependent:['B']},
                                                               {determinate:['C','D'], dependent:['A']},
                                                               {determinate:['C','D'], dependent:['B']},
                                                               {determinate:['D'], dependent:['B']}]);

                                                               */

  });

  it("is able to get find functional dependencies", function() {
    /*
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "2", "3", "5"],
                                            ["3", "2", "5", "6"]]});

    // Do the test(s)
    // If there is no undecided attribute there should be functional dependency being found
    expect(sqlResult.findFunctionalDependencies(["A","B","C","D"],[])).toEqual([]);

    // If there is only one undecided attribute there should be functional dependency being found, too
    expect(sqlResult.findFunctionalDependencies(["A","B","C"],["D"])).toEqual([]);

    // Now it should find two functional dependencies (ABC->D AND ABD->C)
    expect(sqlResult.findFunctionalDependencies(["A","B"],["C","D"])).toEqual([{determinate:['A','B','C'], dependent:['D']},
                                                                               {determinate:['A','B','D'], dependent:['C']}]);

    // Now we should find six functional dependencies - if you are confused why ABC->D and ABD->C are not part (if its recursive)
    // This is due to fact that we only search minimal dependencies
    expect(sqlResult.findFunctionalDependencies(["A"],["B","C","D"])).toEqual([{determinate:['A','B'], dependent:['C']},
                                                                               {determinate:['A','B'], dependent:['D']},
                                                                               {determinate:['A','C'], dependent:['B']},
                                                                               {determinate:['A','C'], dependent:['D']},
                                                                               {determinate:['A','D'], dependent:['B']},
                                                                               {determinate:['A','D'], dependent:['C']}]);
    */

    // Second result
    var sqlResult2 = new SQLResult({columns: ["A", "B", "C", "D"],
                                    values: [["1", "2", "3", "1"],
                                             ["2", "2", "3", "5"],
                                             ["3", "2", "5", "5"]]});

    expect(sqlResult2.findFunctionalDependencies([],["A","B","C","D"])).toEqual([]);
  });

  it("is able to checkFunctionalDependencies", function() {
    // Create a valid SQLResult
    var sqlResult = new SQLResult({columns: ["A", "B", "C", "D"],
                                   values: [["1", "2", "3", "4"],
                                            ["2", "2", "3", "5"],
                                            ["3", "2", "5", "6"]]});

    // Do the test(s)
    // At least one of the parameters is empty => should be false
    expect(sqlResult.checkFunctionalDependency([], ["B"])).toBe(false);
    expect(sqlResult.checkFunctionalDependency(["A"], [])).toBe(false);
    expect(sqlResult.checkFunctionalDependency([], [])).toBe(false);

    // Invalid functional dependencies
    expect(sqlResult.checkFunctionalDependency(["B"], ["A"])).toBe(false);
    expect(sqlResult.checkFunctionalDependency(["C"], ["A"])).toBe(false);
    expect(sqlResult.checkFunctionalDependency(["C"], ["B", "A"])).toBe(false);

    // Valid functional dependencies
    expect(sqlResult.checkFunctionalDependency(["A"], ["B"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["C"], ["B"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["D"], ["A"])).toBe(true);
    expect(sqlResult.checkFunctionalDependency(["A", "B"], ["C"])).toBe(true);
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
