import { FC, useState, useEffect } from "react";
import { IBooking } from "../types/IBooking";
import axios from "axios";

interface IProps {
  setTableData: (state: IBooking[]) => void;
}

const FilterForm: FC<IProps> = ({ setTableData }) => {
  const [requestBody, setRequestBody] = useState<{
    year: number;
    month: number;
  }>({
    year: 2023,
    month: 1,
  });
  const [years, setYears] = useState<{ year: number }[]>([]);
  const [months, setMonths] = useState<string[]>([]);

  const [year, setYear] = useState<number>(2023);
  const [month, setMonth] = useState<number>(1);

  useEffect(() => {
    getFilterBooking(requestBody);
    setYearsHandle();
    setMonths(
      new Array(12)
        .fill(0)
        .map((_, i) =>
          new Date(2000, i, 1).toLocaleString("en", { month: "long" })
        )
    );
  }, []);

  const handleChange = () => {
    setRequestBody({ year: Number(year), month: Number(month) });
  };
  useEffect(() => {
    handleChange();
  }, [year, month]);

  const getFilterBooking = async (requestBody: {
    year: number;
    month: number;
  }) => {
    axios
      .post("http://localhost:8000/api/filterbooking", requestBody)
      .then((response) => {
        setTableData(response.data);
      });
  };

  const setYearsHandle = () => {
    axios.get("http://localhost:8000/api/getYears").then((response) => {
      setYears(response.data);
    });
  };

  return (
    <form className="m-3 w-25">
      <div className="row">
        <div className="col form-group">
          <label className="form-label">Year</label>
          <select
            className="form-select "
            value={year}
            onChange={(e) => {
              getFilterBooking({
                ...requestBody,
                year: Number(e.target.value),
                month: Number(month),
              });
              setYear(Number(e.target.value));
            }}
          >
            {years.map((year) => (
              <option key={year.year} value={year.year}>
                {year.year}
              </option>
            ))}
          </select>
        </div>
        <div className="col form-group">
          <label className="form-label">Month</label>
          <select
            className="form-select"
            value={month}
            onChange={(e) => {
              getFilterBooking({
                ...requestBody,
                month: Number(e.target.value),
                year: Number(year),
              });
              setMonth(Number(e.target.value));
            }}
          >
            {months.map((month, i) => (
              <option key={i} value={i + 1}>
                {month}
              </option>
            ))}
          </select>
        </div>
      </div>
    </form>
  );
};

export default FilterForm;
